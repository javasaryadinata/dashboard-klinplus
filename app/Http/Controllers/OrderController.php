<?php
namespace App\Http\Controllers;

use App\Models\LayananSubkategori;
use App\Models\Order;
use App\Models\OrderDetail;
// use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders     = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori'])->where('status', 'request')->get();
        $pelanggans = Pelanggan::all();
        $promos     = DB::table('promo')->get();
        return view('orders.index', compact('orders', 'pelanggans', 'promos'));
    }

    // public function create()
    // {
    //     $pelanggans = Pelanggan::all();
    //     $layanans = LayananSubkategori::with('rootKategori')->get();
    //     return view('orders.create', compact('pelanggans', 'layanans'));
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'          => 'required|exists:pelanggan,id_pelanggan',
            'alamat_lokasi'         => 'required|string|max:255',
            'lokasi_gmaps'          => 'nullable|string|max:255',
            'catatan'               => 'nullable|string|max:255',
            'tanggal_pengerjaan'    => 'required|date|after_or_equal:today',
            'jam_pengerjaan'        => 'required|date_format:H:i',
            'total_harga'           => 'required|numeric',
            // 'diskon' => 'nullable|numeric',
            'kode'                  => 'nullable|string|max:20',
            'layanan_subkategori'   => 'required|array|min:1',
            'layanan_subkategori.*' => 'exists:layanan_subkategori,id',
            // 'harga_layanan' => 'required|array'
        ]);

        // Ambil diskon dari kode promo jika ada
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
                $layanan = LayananSubkategori::find($id_layanan);
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

    public function show($id)
    {
        $order    = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori'])->findOrFail($id);
        $petugas  = Petugas::all();
        $layanans = LayananSubkategori::with('rootKategori')->get();

        return view('orders.detail', compact('order', 'petugas', 'layanans'));
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
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

    public function approve(Request $request, $id_order)
    {
        $order = Order::findOrFail($id_order);

        // Validasi: Pastikan order belum ada di tabel jadwal
        if (Jadwal::where('id_order', $order->id_order)->exists()) {
            return redirect()->route('orders.index')->with('error', 'Order ini sudah ada di jadwal.');
        }

        // Hitung durasi dan waktu selesai
        $totalDurasi = $order->orderDetails->sum('durasi_layanan');
        $jamMulai = Carbon::parse($order->jam_pengerjaan);
        $jamSelesai = $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i:s');

        $namaPetugas = $order->orderDetails
            ->filter(fn($detail) => $detail->petugas)
            ->pluck('petugas.nama_petugas')
            ->unique()
            ->implode(', ');

        // Buat entri di tabel jadwals
        Jadwal::create([
            'id_order' => $order->id_order,
            'nama_pelanggan' => $order->pelanggan->nama_pelanggan ?? '-',
            'alamat' => $order->alamat_lokasi ?? '-',
            'gmaps' => $order->lokasi_gmaps ?? null,
            'catatan' => $order->catatan ?? null,
            'tanggal_pengerjaan' => $order->tanggal_pengerjaan,
            'waktu_pengerjaan' => $order->jam_pengerjaan,
            'durasi' => $totalDurasi,
            'waktu_selesai' => $jamSelesai,
            'nama_petugas' => $namaPetugas ?: '-',
            'status_pembayaran' => $order->metode_pembayaran ?? '-',
        ]);

        $order->status = 'approved';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order berhasil disetujui dan ditambahkan ke jadwal.');
    }

    protected function generateOrderId(): string
    {
        $now    = Carbon::now();
        $prefix = 'ORD-' . $now->format('ym');

        $lastOrder = Order::where('id_order', 'like', $prefix . '%')
            ->orderBy('id_order', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) substr($lastOrder->id_order, -3) + 1 : 1;

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function updateLayanan(Request $request, $id_order)
    {
        $request->validate([
            'id_order_detail'  => 'array',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_pengerjaan'     => 'required|date_format:H:i',
            'layanans'           => 'array',
            'subtotals'          => 'array',
            'durasi_layanan'   => 'required|array',
            'petugas'          => 'required|array',
            'metode_pembayaran'  => 'required|in:DP,Lunas',
            'tipe_pembayaran'    => 'required|in:Transfer,Cash',
        ]);

        try {
            DB::beginTransaction();

            // Update pembayaran ke tabel 'orders'
            $order = Order::findOrFail($id_order);
            $order->tanggal_pengerjaan  = $request->tanggal_pengerjaan;
            $order->jam_pengerjaan      = $request->jam_pengerjaan;
            $order->metode_pembayaran = $request->metode_pembayaran;
            $order->tipe_pembayaran   = $request->tipe_pembayaran;
            $order->save();

            // Ambil semua id_layanan_subkategori yang masih ada di form
            $layanans = $request->input('layanans', []);

            // Hapus order detail yang tidak ada di form
            OrderDetail::where('id_order', $id_order)
                ->whereNotIn('id_layanan_subkategori', $layanans)
                ->delete();

            // Update detail layanan
            $idDetails = $request->input('id_order_detail', []);
            $durasi    = $request->input('durasi_layanan', []);
            $petugas   = $request->input('petugas', []);

            foreach ($idDetails as $i => $id_detail) {
                $detail = OrderDetail::find($id_detail);
                if ($detail) {
                    $detail->durasi_layanan = $durasi[$i] ?? 60;
                    $detail->id_petugas     = $petugas[$i] ?? null;
                    $detail->save();
                }
            }

            // 2. Tambah layanan baru jika ada
            $layanans  = $request->input('layanans', []);
            $durasiBaru  = $request->input('durasi_layanan', []);
            $petugasBaru = $request->input('petugas', []);
            $subtotals = $request->input('subtotals', []);

            // Cek apakah layanan baru sudah ada di order_detail
            $existingLayananIds = OrderDetail::where('id_order', $id_order)->pluck('id_layanan_subkategori')->toArray();

            foreach ($layanans as $i => $id_layanan) {
                if (!in_array($id_layanan, $existingLayananIds)) {
                    OrderDetail::create([
                        'id_order'               => $id_order,
                        'id_layanan_subkategori' => $id_layanan,
                        'harga'                  => $subtotals[$i] ?? 0,
                        'id_petugas'             => $petugasBaru[$i] ?? null,
                        'durasi_layanan'         => $durasiBaru[$i] ?? 60,
                    ]);
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

    // public function updateLayanan(Request $request, $id_order)
    // {
    //     // dd($request->all());

    //     $request->validate([
    //         'layanans'       => 'required|array',
    //         'subtotals'      => 'required|array',
    //         'petugas'        => 'required|array',
    //         'durasi_layanan' => 'required|array',
    //         // 'nama_petugas' => 'required|array',
    //         // 'estimasi_selesais' => 'required|array',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         // Hapus detail lama
    //         OrderDetail::where('id_order', $id_order)->delete();

    //         // Simpan detail baru
    //         $layanans       = $request->input('layanans', []);
    //         $subtotals      = $request->input('subtotals', []);
    //         $petugas        = $request->input('petugas', []);
    //         $durasi_layanan = $request->input('durasi_layanan', []);

    //         foreach ($layanans as $i => $id_layanan) {
    //             OrderDetail::create([
    //                 'id_order'               => $id_order,
    //                 'id_layanan_subkategori' => $id_layanan,
    //                 // 'estimasi_selesais' => $request->estimasi_selesais[$index],
    //                 'harga'                  => $subtotals[$i],
    //                 'id_petugas'             => $petugas[$i] ?? null,
    //                 // 'nama_petugas' => $request->nama_petugas[$index] ?? null,
    //                 'durasi_layanan'         => $durasi_layanan[$i] ?? 60,
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect()->route('orders.show', $id_order)
    //             ->with('success', 'Layanan berhasil ditambahkan ke order.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return redirect()->back()
    //             ->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage());
    //     }
    // }

}
