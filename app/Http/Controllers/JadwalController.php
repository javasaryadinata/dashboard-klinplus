<?php
namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Petugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['order.pelanggan', 'order.orderDetails.layananSubkategori.rootKategori', 'order.orderDetails.petugas'])
            ->whereIn('status', ['Scheduled'])
            ->get();
        return view('jadwal.index', compact('jadwals'));
    }

    // public function show($id_order)
    // {
    //     $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();
    //     return view('jadwal.reschedule', compact('jadwal'));
    // }

    // public function rescheduleForm(Request $request, $id_order)
    // {
    //     $order = Order::with(['orderDetails.petugas', 'pelanggan'])->findOrFail($id_order);
    //     $layanans = LayananSubkategori::with('rootKategori')->get();
    //     $petugas = Petugas::all();
    //     return view('jadwal.reschedule', compact('order', 'layanans', 'petugas'));
    // }

    public function downloadWorkingOrder($id_order)
    {
        $order = Order::with([
            'pelanggan',
            'orderDetails.layananSubkategori.rootKategori',
            'orderDetails.petugas'
        ])->where('id_order', $id_order)->firstOrFail();

        // Kirim ke view working_order.blade.php (buat view ini)
        $pdf = PDF::loadView('jadwal.working_order', compact('order'));

        // Nama file WO
        $filename = 'WorkingOrder-' . $order->id_order . '.pdf';

        return $pdf->download($filename);
    }

    public function doReschedule(Request $request, $id_order)
    {
        // dd('MASUK CONTROLLER');

        DB::beginTransaction();
        try {
            $oldOrder = Order::with(['orderDetails.petugas'])->findOrFail($id_order);

            // Generate ID order baru (pakai method di OrderController, atau copy saja)
            $newIdOrder = (new \App\Http\Controllers\OrderController)->generateOrderId();

            // Buat order baru (boleh custom field sesuai kebutuhan)
            $newOrder = Order::create([
                'id_order'           => $newIdOrder,
                'id_pelanggan'       => $oldOrder->id_pelanggan,
                'alamat_lokasi'      => $oldOrder->alamat_lokasi,
                'lokasi_gmaps'       => $oldOrder->lokasi_gmaps,
                'catatan'            => $request->input('catatan', $oldOrder->catatan),
                'tanggal_pengerjaan' => $request->tanggal_pengerjaan,
                'jam_pengerjaan'     => $request->jam_pengerjaan,
                'total_harga'        => $oldOrder->total_harga,
                'diskon'             => $oldOrder->diskon,
                'kode'               => $oldOrder->kode,
                'metode_pembayaran'  => $oldOrder->metode_pembayaran,
                'tipe_pembayaran'    => $oldOrder->tipe_pembayaran,
                'status'             => 'Request',
                'reschedule_from'    => $oldOrder->id_order,
                'alasan_reschedule'  => $request->input('alasan_reschedule', null),
            ]);

            // Copy order detail & petugas
            foreach ($oldOrder->orderDetails as $oldDetail) {
                $newDetail = $newOrder->orderDetails()->create([
                    'id_layanan_subkategori' => $oldDetail->id_layanan_subkategori,
                    'harga'                  => $oldDetail->harga,
                    'durasi_layanan'         => $oldDetail->durasi_layanan,
                    'subtotal'               => $oldDetail->subtotal,
                ]);
                // Copy petugas
                foreach ($oldDetail->petugas as $ptg) {
                    $newDetail->petugas()->attach($ptg->id_petugas);
                }
            }

            // Update status order lama
            $oldOrder->status = 'Rescheduled';
            $oldOrder->save();

            // Update status pada tabel jadwals juga
            Jadwal::where('id_order', $oldOrder->id_order)->update(['status' => 'Rescheduled']);

            DB::commit();

            return redirect()->route('orders.show', $newOrder->id_order)
                ->with('success', 'Order berhasil direschedule.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reschedule: ' . $e->getMessage());
        }
    }

    public function rescheduleUpdate(Request $request, $id_order)
    {
        $order = Order::where('id_order', $id_order)->firstOrFail();
        // validasi dan update data
        $order->tanggal_pengerjaan = $request->tanggal_pengerjaan;
        $order->jam_pengerjaan     = $request->jam_pengerjaan;
        // ...update field lain...
        $order->status = 'Rescheduled';
        $order->save();

        return redirect()->route('jadwal.index')->with('success', 'Order berhasil di-reschedule.');
    }

    // public function reschedule($id_order)
    // {
    //     // Ambil order lama
    //     $oldOrder = Order::where('id_order', $id_order)->firstOrFail();

    //     // Generate ID order baru dengan format: ORD-ymdNNN
    //     $now       = now();
    //     $prefix    = 'ORD-' . $now->format('ym');
    //     $lastOrder = Order::where('id_order', 'like', $prefix . '%')
    //         ->orderBy('id_order', 'desc')
    //         ->first();
    //     $lastNumber = $lastOrder ? (int) substr($lastOrder->id_order, -3) : 0;
    //     $newNumber  = $lastNumber + 1;
    //     $newIdOrder = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    //     // Duplikasi order lama ke order baru
    //     $newOrder                     = $oldOrder->replicate();
    //     $newOrder->id_order           = $newIdOrder;
    //     $newOrder->status             = 'request';                     // Atau status lain sesuai kebutuhan
    //     $newOrder->tanggal_pengerjaan = $oldOrder->tanggal_pengerjaan; // Atur tanggal baru nanti
    //     $newOrder->jam_pengerjaan     = $oldOrder->jam_pengerjaan;     // Atur jam baru nanti
    //     $newOrder->save();

    //     // Duplikasi order detail
    //     $oldDetails = OrderDetail::where('id_order', $id_order)->get();
    //     foreach ($oldDetails as $detail) {
    //         $newDetail           = $detail->replicate();
    //         $newDetail->id_order = $newIdOrder;
    //         $newDetail->save();
    //     }

    //     // (Opsional) Update status jadwal lama jika masih pakai tabel jadwals
    //     $jadwal = Jadwal::where('id_order', $id_order)->first();
    //     if ($jadwal) {
    //         $jadwal->status = 'rescheduled';
    //         $jadwal->save();
    //     }

    //     return redirect()->route('jadwal.rescheduleForm', $newIdOrder)
    //         ->with('success', 'Order baru untuk reschedule berhasil dibuat. Silakan atur tanggal dan waktu baru.');
    // }

    public function update(Request $request, $id_order)
    {
        $jadwal = Jadwal::where('id_order', $id_order)->firstOrFail();

        $jadwal->update([
            'status'             => 'rescheduled',
            'tanggal_pengerjaan' => $request->input('tanggal_pengerjaan'),
            'waktu_pengerjaan'   => $request->input('waktu_pengerjaan'),
            // tambahkan field lain yang ingin di update
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil direschedule.');
    }

    public function selesai($id_order)
    {
        // Ubah status di tabel orders
        $order         = Order::where('id_order', $id_order)->firstOrFail();
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

    public function destroy($id_order)
    {
        // Hapus order detail terlebih dahulu (jika ada relasi)
        OrderDetail::where('id_order', $id_order)->delete();

        // Hapus order utama
        $order = Order::where('id_order', $id_order)->firstOrFail();
        $order->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
