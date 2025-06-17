<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pelanggan;
// use App\Models\Layanan;
use App\Models\LayananSubkategori;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori'])->get();
        $pelanggans = Pelanggan::all();
        $promos = DB::table('promo')->get();
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
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'alamat_lokasi' => 'required|string|max:255',
            'lokasi_gmaps' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_pengerjaan' => 'required|date_format:H:i',
            'total_harga' => 'required|numeric',
            // 'diskon' => 'nullable|numeric',
            'kode' => 'nullable|string|max:20',
            'layanan_subkategori' => 'required|array|min:1',
            'layanan_subkategori.*' => 'exists:layanan_subkategori,id',
            // 'harga_layanan' => 'required|array'
        ]);

        // Ambil diskon dari kode promo jika ada
        $diskon = 0;
        if (!empty($validated['kode'])) {
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
                'id_order' => $this->generateOrderId(),
                'id_pelanggan' => $validated['id_pelanggan'],
                'alamat_lokasi' => $validated['alamat_lokasi'],
                'lokasi_gmaps' => $validated['lokasi_gmaps'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'tanggal_pengerjaan' => $validated['tanggal_pengerjaan'],
                'jam_pengerjaan' => $validated['jam_pengerjaan'],
                'total_harga' => $validated['total_harga'],
                'diskon' => $diskon,
                'kode' => $validated['kode'] ?? null
            ]);

            foreach ($request->layanan_subkategori as $id_layanan) {
                $layanan = LayananSubkategori::find($id_layanan);
                OrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_layanan_subkategori' => $id_layanan,
                    'harga' => $layanan->harga,
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
        $order = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori'])->findOrFail($id);
        $petugas = Petugas::all();
        $layanans = LayananSubkategori::with('rootKategori')->get();

        return view('orders.detail', compact('order', 'petugas', 'layanans'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'alamat_lokasi' => 'required|string|max:255',
            'lokasi_gmaps' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'tanggal_pengerjaan' => 'required|date|after_or_equal:today',
            'jam_pengerjaan' => 'required|date_format:H:i',
            'total_harga' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'kode' => 'nullable|string|max:20'
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

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'approved';
        $order->save();

        return redirect()->route('orders.detail', $id)
            ->with('success', 'Order berhasil di-approve.');
    }

    protected function generateOrderId(): string
    {
        $now = Carbon::now();
        $prefix = 'ORD-' . $now->format('ym');
        
        $lastOrder = Order::where('id_order', 'like', $prefix.'%')
            ->orderBy('id_order', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) substr($lastOrder->id_order, -3) + 1 : 1;
        
        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function updateLayanan(Request $request, $id_order)
    {
        // dd($request->all());

        $request->validate([
            'layanans' => 'required|array',
            'subtotals' => 'required|array',
            'petugas' => 'required|array',
            // 'nama_petugas' => 'required|array',
            // 'estimasi_selesais' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            OrderDetail::where('id_order', $id_order)->delete();
            foreach ($request->layanans as $index => $id_layanan_subkategori) {
                OrderDetail::create([
                    'id_order' => $id_order,
                    'id_layanan_subkategori' => $id_layanan_subkategori,
                    // 'estimasi_selesais' => $request->estimasi_selesais[$index],
                    'harga' => $request->subtotals[$index],
                    'id_petugas' => $request->petugas[$index] ?? null,
                    // 'nama_petugas' => $request->nama_petugas[$index] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $id_order)
                ->with('success', 'Layanan berhasil ditambahkan ke order.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage());
        }
    }
}
