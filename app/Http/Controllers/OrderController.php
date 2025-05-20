<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function index()
{
    $orders = Order::with(['pelanggan', 'layanans'])->get();
    
    // Pastikan ambil kolom yang diperlukan termasuk alamat dan gmaps
    $pelanggans = Pelanggan::select('id_pelanggan', 'nama_pelanggan', 'alamat', 'gmaps')->get();
    
    $layanans = Layanan::all();
    
    return view('orders.index', compact('orders', 'pelanggans', 'layanans'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan',
        'tanggal_pembersihan' => [
            'required',
            'date',
            'after_or_equal:' . now()->toDateString()
        ],
        'waktu_pembersihan' => 'required|date_format:H:i',
        'gmaps' => 'nullable|url|max:500',
        'alamat' => 'required|string|max:255'
    ]);

    try {
        DB::beginTransaction();

        Order::create([
            'id_order' => $this->generateOrderId(),
            'id_pelanggan' => $validated['id_pelanggan'],
            'gmaps' => $validated['gmaps'] ?? null,
            'alamat' => $validated['alamat'],
            'tanggal_pembersihan' => $validated['tanggal_pembersihan'],
            'waktu_pembersihan' => $validated['waktu_pembersihan'],
            'status' => 'request'
        ]);

        DB::commit();

        return redirect()->route('orders.index')
            ->with('success', 'Order baru berhasil dibuat.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal membuat order: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $order = Order::with(['pelanggan', 'layanans.pricelist'])->findOrFail($id);
        $pelanggan = $order->pelanggan;
        $layanans = Layanan::all();
        $petugas = Petugas::all(); 
        return view('orders.detail', compact('order', 'layanans', 'petugas'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:request,confirmed,in_progress,completed,cancelled',
            'tanggal_pembersihan' => 'sometimes|date|after_or_equal:today',
            'waktu_pembersihan' => 'sometimes|date_format:H:i',
            'gmaps' => 'sometimes|nullable|url|max:500',
            'alamat' => 'sometimes|string|max:255'
        ]);

        $order = Order::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            $order->update($validated);
            DB::commit();

            return redirect()->route('orders.show', $id)
                ->with('success', 'Order berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            $order->delete();
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus order: ' . $e->getMessage());
        }
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
    $request->validate([
        'layanans' => 'required|array',
    'subtotals' => 'required|array',
    'estimasi_selesais' => 'required|array',
    ]);

    try {
        DB::beginTransaction();

        foreach ($request->layanans as $index => $id_layanan) {
            OrderDetail::create([
                'id_order' => $id_order,
                'id_layanan' => $id_layanan,
                'estimasi_selesai' => $request->estimasi_selesais[$index],
                'sub_total' => $request->subtotals[$index],
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
