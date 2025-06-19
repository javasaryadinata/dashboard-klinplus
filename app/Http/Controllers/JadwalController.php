<?php

namespace App\Http\Controllers;

use App\Models\LayananSubkategori;
use App\Models\Jadwal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pelanggan;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Order::with(['pelanggan', 'orderDetails.layananSubkategori.rootKategori', 'orderDetails.petugas'])
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->get();
        return view('jadwal.index', compact('jadwals'));
    }

    // public function show($id_order)
    // {
    //     $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
    //     return view('jadwal.detail', compact('jadwal'));
    // }

    public function destroy($id_order)
    {
        // Hapus order detail terlebih dahulu (jika ada relasi)
        OrderDetail::where('id_order', $id_order)->delete();

        // Hapus order utama
        $order = Order::where('id_order', $id_order)->firstOrFail();
        $order->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function rescheduleForm(Request $request, $id_order)
    {
        $order = Order::where('id_order', $id_order)->firstOrFail();
        // Ambil order lama dari session atau dari parameter
        $oldOrder = $request->session()->get('oldOrder');
        if (!$oldOrder && $request->has('old_order_id')) {
            $oldOrder = Order::where('id_order', $request->old_order_id)->first();
        }
        // kirim data layanan, petugas, dll
        $petugas  = Petugas::all();
        $layanans = LayananSubkategori::with('rootKategori')->get();
        return view('jadwal.reschedule', compact('order', 'petugas', 'layanans'));
    }

    public function rescheduleUpdate(Request $request, $id_order)
    {
        $order = Order::where('id_order', $id_order)->firstOrFail();
        // validasi dan update data
        $order->tanggal_pengerjaan = $request->tanggal_pengerjaan;
        $order->jam_pengerjaan = $request->jam_pengerjaan;
        // ...update field lain...
        $order->status = 'rescheduled';
        $order->save();

        return redirect()->route('jadwal.index')->with('success', 'Order berhasil di-reschedule.');
    }

    public function reschedule($id_order)
    {
        // Ambil order lama
        $oldOrder = Order::where('id_order', $id_order)->firstOrFail();

        // Generate ID order baru dengan format: ORD-ymdNNN
        $now = now();
        $prefix = 'ORD-' . $now->format('ym');
        $lastOrder = Order::where('id_order', 'like', $prefix . '%')
            ->orderBy('id_order', 'desc')
            ->first();
        $lastNumber = $lastOrder ? (int)substr($lastOrder->id_order, -3) : 0;
        $newNumber = $lastNumber + 1;
        $newIdOrder = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Duplikasi order lama ke order baru
        $newOrder = $oldOrder->replicate();
        $newOrder->id_order = $newIdOrder;
        $newOrder->status = 'request'; // Atau status lain sesuai kebutuhan
        $newOrder->tanggal_pengerjaan = $oldOrder->tanggal_pengerjaan; // Atur tanggal baru nanti
        $newOrder->jam_pengerjaan = $oldOrder->jam_pengerjaan;     // Atur jam baru nanti
        $newOrder->save();

        // Duplikasi order detail
        $oldDetails = OrderDetail::where('id_order', $id_order)->get();
        foreach ($oldDetails as $detail) {
            $newDetail = $detail->replicate();
            $newDetail->id_order = $newIdOrder;
            $newDetail->save();
        }

        // (Opsional) Update status jadwal lama jika masih pakai tabel jadwals
        $jadwal = Jadwal::where('id_order', $id_order)->first();
        if ($jadwal) {
            $jadwal->status = 'rescheduled';
            $jadwal->save();
        }

        return redirect()->route('jadwal.rescheduleForm', $newIdOrder)
            ->with('success', 'Order baru untuk reschedule berhasil dibuat. Silakan atur tanggal dan waktu baru.');
    }

    public function update(Request $request, $id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();

        $jadwal->update([
            'status' => 'rescheduled',
            'tanggal_pengerjaan' => $request->input('tanggal_pengerjaan'),
            'waktu_pengerjaan' => $request->input('waktu_pengerjaan'),
            // tambahkan field lain yang ingin di update
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil direschedule.');
    }

    public function selesai($id_order)
    {
        // Ubah status di tabel orders
        $order = Order::where('id_order', $id_order)->firstOrFail();
        $order->status = 'selesai';
        $order->save();

        // (Opsional) Jika masih pakai tabel jadwals, update juga di sana
        $jadwal = Jadwal::where('id_order', $id_order)->first();
        if ($jadwal) {
            $jadwal->status = 'selesai';
            $jadwal->save();
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diselesaikan.');
        }
}
