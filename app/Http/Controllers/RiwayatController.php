<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        // Tampilkan order yang statusnya sudah "riwayat" (rescheduled, selesai, cancel)
        $riwayats = Order::with([
                'pelanggan',
                'orderDetails.layananSubkategori.rootKategori',
                'orderDetails.petugas'
            ])
            ->whereIn('status', ['Rescheduled', 'Selesai', 'Cancel']) // bisa tambahkan status lain jika perlu
            ->orderByDesc('created_at')
            ->get();

        return view('riwayat.index', compact('riwayats'));
    }
}
