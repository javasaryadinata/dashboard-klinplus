<?php
namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\LayananSubkategori;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailPetugas;
use App\Models\Pelanggan;
use App\Models\Petugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->query('search');
        $sort      = $request->query('sort', 'default');
        $direction = $request->query('direction', 'desc');

        $ordersQuery = Order::query()->with(['pelanggan.kota', 'orderDetails.layananSubkategori.rootKategori'])->where('status', 'Request');

        if ($search) {
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('id_order', 'like', "%$search%")
                    ->orWhereHas('pelanggan', function ($sub) use ($search) {
                        $sub->where('nama_pelanggan', 'like', "%$search%");
                    });
            });
        }

        switch ($sort) {
            case 'id_order':
                $ordersQuery->orderBy('id_order', $direction);
                break;
            case 'pelanggan':
                $ordersQuery->join('pelanggan', 'orders.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                    ->orderBy('pelanggan.nama_pelanggan', $direction)
                    ->select('orders.*');
                break;
            case 'tanggal_pengerjaan':
                $ordersQuery->orderBy('tanggal_pengerjaan', $direction)->orderBy('jam_pengerjaan', $direction);
                break;
            case 'default':
            default:
                $ordersQuery->orderBy('created_at', 'desc');
                break;
        }

        $orders     = $ordersQuery->get();
        $pelanggans = Pelanggan::all();
        $promos     = DB::table('promo')->get();

        return view('orders.index', compact('orders', 'pelanggans', 'promos', 'sort', 'direction', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'          => 'required|exists:pelanggan,id_pelanggan',
            'alamat_lokasi'         => 'required_with:id_pelanggan|nullable|string|max:255',
            'lokasi_gmaps'          => 'nullable|string|max:255',
            'catatan'               => 'nullable|string|max:255',
            'tanggal_pengerjaan'    => 'required|date|after_or_equal:today',
            'jam_pengerjaan'        => 'required|date_format:H:i',
            'total_harga'           => 'required|numeric',
            'kode'                  => 'nullable|string|max:20',
            'layanan_subkategori'   => 'required|array|min:1',
            'layanan_subkategori.*' => 'exists:layanan_subkategori,id',
        ], [
            'layanan_subkategori.required' => 'Minimal satu layanan harus ditambahkan.',
            'id_pelanggan.exists'          => 'Pelanggan yang dipilih tidak valid atau belum terdaftar.',
            'id_pelanggan.required'        => 'Pelanggan belum di pilih.',
        ]);

        $diskon = 0;
        if (! empty($validated['kode'])) {
            $promo = DB::table('promo')
                ->whereRaw('LOWER(kode) = ?', [strtolower($validated['kode'])])
                ->first();
            if ($promo) {
                $diskon = $promo->diskon;
            }
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'id_order'           => $this->generateOrderId(),
                'id_pelanggan'       => $validated['id_pelanggan'],
                'alamat_lokasi'      => $validated['alamat_lokasi'],
                'lokasi_gmaps'       => $validated['lokasi_gmaps'] ?? null,
                'catatan'            => $validated['catatan'] ?? null,
                'tanggal_pengerjaan' => $validated['tanggal_pengerjaan'],
                'jam_pengerjaan'     => $validated['jam_pengerjaan'],
                'total_harga'        => $validated['total_harga'],
                'diskon'             => $diskon,
                'kode'               => $validated['kode'] ?? null,
            ]);

            foreach ($request->layanan_subkategori as $id_layanan) {
                $layanan = LayananSubkategori::query()->find($id_layanan);
                OrderDetail::create([
                    'id_order'               => $order->id_order,
                    'id_layanan_subkategori' => $id_layanan,
                    'harga'                  => $layanan->harga,
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order baru berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $order    = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori', 'orderDetails.petugas'])->findOrFail($id);
        $layanans = LayananSubkategori::with('rootKategori')->get();
        $petugas  = Petugas::all();

        return view('orders.detail', compact('order', 'layanans', 'petugas'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'alamat_lokasi'      => 'required|string|max:255',
            'lokasi_gmaps'       => 'nullable|string|max:255',
            'catatan'            => 'nullable|string|max:255',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_pengerjaan'     => 'required|date_format:H:i',
            'total_harga'        => 'required|numeric',
            'diskon'             => 'nullable|numeric',
            'kode'               => 'nullable|string|max:20',
            'metode_pembayaran'  => 'required|in:DP,Lunas',
            'tipe_pembayaran'    => 'required|in:Transfer,Cash',
        ]);

        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->update($validated);
            DB::commit();
            return redirect()->route('orders.show', $id)->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }

    public function invoicePdf(string $id_order)
    {
        $order = Order::with(['pelanggan', 'orderDetails.layananSubkategori', 'orderDetails.petugas'])->findOrFail($id_order);

        $pdf = Pdf::loadView('orders.invoice_pdf', compact('order'));
        return $pdf->download('Invoice_' . $order->id_order . '.pdf');
    }

    public function previewInvoice(string $id_order)
    {
        $order = Order::with(['pelanggan', 'orderDetails.layananSubkategori', 'orderDetails.petugas'])->findOrFail($id_order);

        return view('orders.invoice_pdf', compact('order'));
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->orderDetails()->delete();
            $order->delete();
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus order: ' . $e->getMessage());
        }
    }

    public function cancel(string $id_order)
    {
        $order = Order::query()->where('id_order', $id_order)->firstOrFail();

        $order->status = 'Canceled';
        $order->save();

        $jadwal = Jadwal::query()->where('id_order', $id_order)->first();
        if ($jadwal) {
            $jadwal->status = 'Canceled';
            $jadwal->save();
        }

        return redirect()->route('riwayat.index')->with('success', 'Order berhasil dibatalkan.');
    }

    public function approve(Request $request, string $id_order)
    {
        DB::beginTransaction();
        try {
            $order = Order::with(['pelanggan', 'orderDetails.petugas'])->findOrFail($id_order);

            if ($order->status === 'Scheduled') {
                return redirect()->route('orders.index')->with('error', 'Order ini sudah dijadwalkan.');
            }

            $order->status = 'Scheduled';
            $order->save();

            $jadwal = Jadwal::query()->where('id_order', $order->id_order)->first();
            if (! $jadwal) {
                $totalDurasi = $order->orderDetails->sum('durasi_layanan');
                $jamMulai    = Carbon::parse($order->jam_pengerjaan);
                $jamSelesai  = $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i:s');

                $namaPetugas = $order->orderDetails->flatMap->petugas->pluck('nama_petugas')->unique()->implode(', ');

                Jadwal::create([
                    'status'             => 'Scheduled',
                    'id_order'           => $order->id_order,
                    'nama_pelanggan'     => $order->pelanggan->nama_pelanggan ?? '-',
                    'alamat'             => $order->alamat_lokasi ?? '-',
                    'gmaps'              => $order->lokasi_gmaps ?? null,
                    'catatan'            => $order->catatan ?? null,
                    'tanggal_pengerjaan' => $order->tanggal_pengerjaan,
                    'waktu_pengerjaan'   => $order->jam_pengerjaan,
                    'durasi'             => $totalDurasi,
                    'waktu_selesai'      => $jamSelesai,
                    'nama_petugas'       => $namaPetugas ?: '-',
                    'status_pembayaran'  => $order->metode_pembayaran ?? '-',
                ]);
            }

            DB::commit();
            return redirect()->route('jadwal.index', $id_order)->with('success', 'Order berhasil disetujui dan dijadwalkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('orders.show', $id_order)->with('error', 'Gagal approve order: ' . $e->getMessage());
        }
    }

    public function generateOrderId(): string
    {
        $now    = Carbon::now();
        $prefix = 'ORD-' . $now->format('ym');

        $lastOrder = Order::query()->where('id_order', 'like', $prefix . '%')
            ->orderBy('id_order', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) substr($lastOrder->id_order, -3) + 1 : 1;

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function updateLayanan(Request $request, string $id_order)
    {
        $request->validate([
            'id_order_detail'    => 'required|array',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_pengerjaan'     => 'required|date_format:H:i',
            'layanans'           => 'required|array',
            'subtotals'          => 'required|array',
            'durasi_layanan'     => 'required|array',
            'diskon'             => 'nullable|numeric|min:0',
            'total_harga'        => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|in:DP,Lunas',
            'tipe_pembayaran'    => 'required|in:Transfer,Cash',
        ]);

        foreach ($request->layanans as $i => $id_layanan) {
            $petugasArr = $request->petugas[$i] ?? [];
            if (empty(array_filter($petugasArr))) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan! Petugas belum dipilih untuk layanan ' . ($i + 1) . '.');
            }
        }

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id_order);
            $order->update([
                'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
                'jam_pengerjaan'     => $request->jam_pengerjaan,
                'diskon'             => $request->diskon ?? 0,
                'total_harga'        => $request->total_harga,
                'metode_pembayaran'  => $request->metode_pembayaran,
                'tipe_pembayaran'    => $request->tipe_pembayaran,
            ]);

            OrderDetail::query()->where('id_order', $id_order)
                ->whereNotIn('id_layanan_subkategori', $request->layanans)
                ->delete();

            foreach ($request->layanans as $i => $id_layanan) {
                $id_order_detail = $request->id_order_detail[$i];
                $durasi          = $request->durasi_layanan[$i] ?? 60;
                $subtotal        = $request->subtotals[$i] ?? 0;
                $petugasArr      = $request->petugas[$i] ?? [];

                if ($id_order_detail) {
                    $detail = OrderDetail::query()->find($id_order_detail);
                    if ($detail) {
                        $detail->durasi_layanan = $durasi;
                        $detail->harga          = $subtotal;
                        $detail->save();

                        OrderDetailPetugas::query()->where('id_order_detail', $detail->id_order_detail)->delete();
                        foreach (array_filter($petugasArr) as $id_petugas) {
                            OrderDetailPetugas::create([
                                'id_order_detail' => $detail->id_order_detail,
                                'id_petugas'      => $id_petugas,
                            ]);
                        }
                    }
                } else {
                    $exists = OrderDetail::query()->where('id_order', $id_order)
                        ->where('id_layanan_subkategori', $id_layanan)
                        ->exists();
                    if (! $exists) {
                        $detail = OrderDetail::create([
                            'id_order'               => $id_order,
                            'id_layanan_subkategori' => $id_layanan,
                            'durasi_layanan'         => $durasi,
                            'harga'                  => $subtotal,
                        ]);
                        foreach (array_filter($petugasArr) as $id_petugas) {
                            OrderDetailPetugas::create([
                                'id_order_detail' => $detail->id_order_detail,
                                'id_petugas'      => $id_petugas,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $id_order)
                ->with('success', 'Perubahan berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui layanan: ' . $e->getMessage());
        }
    }
}
