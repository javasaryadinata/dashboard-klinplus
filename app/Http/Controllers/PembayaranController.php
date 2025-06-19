<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PembayaranController extends Controller
{
    public function index()
    {
        $orders = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori', 'orderDetails.petugas'])
            ->where('status', 'selesai')
            ->get();

        return view('pembayaran.index', compact('orders'));
    }
}
