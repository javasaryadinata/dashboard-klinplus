<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort   = $request->query('sort', 'desc');

        $orders = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori', 'orderDetails.petugas'])
            ->where('status', 'Selesai')->where('metode_pembayaran', 'DP')
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('id_order', 'like', "%$search%")
                    ->orWhereHas('pelanggan', function ($sub) use ($search) {
                        $sub->where('nama_pelanggan', 'like', "%$search%");
                    });
                });
            })
            ->orderBy('tanggal_pengerjaan', $sort)
            ->get();

        return view('pembayaran.index', compact('orders', 'search', 'sort'));
    }

    public function setLunas($id_order)
    {
        $order = Order::where('id_order', $id_order)->firstOrFail();
        $order->metode_pembayaran = 'Lunas';
        $order->save();

        return redirect()->route('riwayat.index')->with('success', 'Order berhasil dilunasi!');
    }
}
