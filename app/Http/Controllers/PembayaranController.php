<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PembayaranController extends Controller
{
    public function index()
    {
        $orders = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori', 'orderDetails.petugas'])
            ->where('status', 'Selesai')->where('metode_pembayaran', 'DP')
            ->get();

        return view('pembayaran.index', compact('orders'));
    }

    public function setLunas($id_order)
    {
        $order = Order::where('id_order', $id_order)->firstOrFail();
        $order->metode_pembayaran = 'Lunas';
        $order->save();

        return redirect()->route('riwayat.index')->with('success', 'Order berhasil dilunasi!');
    }
}
