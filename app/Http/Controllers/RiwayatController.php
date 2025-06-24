<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort   = $request->query('sort', 'desc');
        $status = $request->query('status');
        
        $ordersQuery = Order::with([
            'pelanggan',
            'orderDetails.layananSubkategori.rootKategori',
            'orderDetails.petugas'
        ])
        ->whereIn('status', ['Selesai', 'Canceled', 'Rescheduled']);

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        if ($search) {
            $ordersQuery->where(function($q) use ($search) {
                $q->where('id_order', 'like', "%$search%")
                ->orWhereHas('pelanggan', function ($sub) use ($search) {
                    $sub->where('nama_pelanggan', 'like', "%$search%");
                })
                ->orWhereHas('orderDetails.petugas', function ($sub) use ($search) {
                    $sub->where('nama_petugas', 'like', "%$search%");
                });
            });
        }

        $orders = $ordersQuery
            ->orderBy('tanggal_pengerjaan', $sort)
            ->get();                                                                                                                                    

        return view('riwayat.index', compact('orders', 'search', 'sort', 'status'));
    }
}
