<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::all(); // Ambil semua data jadwal
        return view('jadwal.index', compact('jadwals'));
    }

    public function show($id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
        return view('jadwal.detail', compact('jadwal'));
    }

    public function destroy($id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function reschedule($id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
        // Logika untuk menampilkan form reschedule atau langsung mengubah data
        return view('jadwal.reschedule', compact('jadwal'));
    }

    public function update(Request $request, $id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();

        $jadwal->update([
            'tanggal_pengerjaan' => $request->input('tanggal_pengerjaan'),
            'waktu_pengerjaan' => $request->input('waktu_pengerjaan'),
            // tambahkan field lain yang ingin di update
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil direschedule.');
    }

    public function selesai($id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
        $jadwal->status = 'selesai';
        $jadwal->save();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diselesaikan.');
    }
}
