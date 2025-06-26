<?php
namespace App\Http\Controllers;

use App\Models\LayananRootKategori;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Mail\InvoiceBookingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class BookingFormController extends Controller
{
    public function showBookingForm()
    {
        try {
            $kota    = DB::table('kota')->orderBy('nama_kota', 'desc')->get();
            $layanan = LayananRootKategori::with(['subkategori' => function ($query) {
                $query->select('id', 'layanan_rootkategori_id', 'nama_subkategori',
                    DB::raw('CAST(harga AS DECIMAL(10,2)) as harga'));
            }])->get();

            return view('form.booking-form', [
                'kota'    => $kota,
                'layanan' => $layanan,
            ]);
        } catch (\Exception $e) {
            return back()->withError('Gagal memuat data kota: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique Pelanggan ID in format CSYYMMNNN
     */
    protected function generatePelangganId(): string
    {
        $now           = Carbon::now();
        $prefix        = 'CS' . $now->format('ym');
        $lastPelanggan = Pelanggan::where('id_pelanggan', 'like', $prefix . '%')
            ->orderBy('id_pelanggan', 'desc')
            ->first();
        $sequence = $lastPelanggan ? (int) substr($lastPelanggan->id_pelanggan, -3) + 1 : 1;
        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    protected function generateOrderId(): string
    {
        $now       = Carbon::now();
        $prefix    = 'ORD-' . $now->format('ym');
        $lastOrder = Order::where('id_order', 'like', $prefix . '%')
            ->orderBy('id_order', 'desc')
            ->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->id_order, -3) + 1 : 1;
        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    // public function savePelanggan($request)
    // {
    //     $pelanggan = DB::table('pelanggan')
    //         ->where('telp_pelanggan', $request->whatsapp)
    //         ->first();

    //     if (! $pelanggan) {
    //         return DB::table('pelanggan')->insertGetId([
    //             'nama_pelanggan' => $request->nama_lengkap,
    //             'telp_pelanggan' => $request->whatsapp,
    //             'email'          => $request->email,
    //             'id_kota'        => $request->kota,
    //             'alamat_lokasi'  => $request->alamat,
    //             'lokasi_gmaps'   => $request->maps,
    //             'catatan'        => $request->catatan,
    //             'created_at'     => now(),
    //             'updated_at'     => now(),
    //         ]);
    //     }

    //     return $pelanggan->id_pelanggan;
    // }

    public function checkPromo(Request $request)
    {
        $kode  = $request->query('kode');
        $promo = \App\Models\Promo::whereRaw('LOWER(kode) = ?', [strtolower($kode)])->first();

        if ($promo) {
            return response()->json([
                'valid'   => true,
                'diskon'  => $promo->diskon,
                'message' => 'Kode promo valid',
            ]);
        } else {
            return response()->json([
                'valid'   => false,
                'diskon'  => 0,
                'message' => 'Kode promo tidak ditemukan.',
            ], 404);
        }
    }

    public function storeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'whatsapp'     => 'required|string|max:13',
            'email'        => 'nullable|email|max:100',
            'kota'         => 'required|exists:kota,id_kota',
            'maps'         => 'nullable|url',
            'alamat'       => 'required|string',
            'catatan'      => 'nullable|string',
            'tanggal'      => 'required|date',
            'waktu'        => 'required',
            'layanan'      => 'required|array',
            'layanan.*'    => 'exists:layanan_subkategori,id',
            'promo'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $pelanggan = Pelanggan::where('telp_pelanggan', $request->whatsapp)->first();
        if (! $pelanggan) {
            $idPelanggan = $this->generatePelangganId();
            $pelanggan   = Pelanggan::create([
                'id_pelanggan'   => $idPelanggan,
                'nama_pelanggan' => $request->nama_lengkap,
                'telp_pelanggan' => $request->whatsapp,
                'email'          => $request->email,
                'id_kota'        => $request->kota,
                'alamat_lokasi'  => $request->alamat,
                'lokasi_gmaps'   => $request->maps,
                'catatan'        => $request->catatan,
            ]);
        }

        // Check jika pelanggan sudah ada
        // $pelanggan = DB::table('pelanggan')->where('telp_pelanggan', $request->whatsapp)->first();

        // if (!$pelanggan) {
        //     $idPelanggan = DB::table('pelanggan')->insertGetId([
        //         'nama_pelanggan' => $request->nama_lengkap,
        //         'telp_pelanggan' => $request->whatsapp,
        //         'email'          => $request->email,
        //         'id_kota'        => $request->kota,
        //         'alamat_lokasi'  => $request->alamat,
        //         'lokasi_gmaps'   => $request->maps,
        //         'catatan'        => $request->catatan,
        //         'created_at'     => now(),
        //         'updated_at'     => now(),
        //     ]);
        // } else {
        //     $idPelanggan = $pelanggan->id_pelanggan;
        // }

        // Hitung total harga
        $layananIds = $request->layanan;
        $totalHarga = DB::table('layanan_subkategori')
            ->whereIn('id', $layananIds)
            ->sum('harga');

        // Cek promo
        $diskon    = 0;
        $kodePromo = $request->promo;
        if ($kodePromo) {
            $promo = DB::table('promo')
                ->whereRaw('LOWER(kode) = ?', [strtolower($kodePromo)])
                ->first();
            if ($promo) {
                $diskon = floatval($promo->diskon);
            }
        }

        // Simpan order
        $idOrder = $this->generateOrderId();
        $order   = Order::create([
            'id_order'           => $idOrder,
            'id_pelanggan'       => $pelanggan->id_pelanggan,
            'tanggal_pengerjaan' => $request->tanggal,
            'jam_pengerjaan'     => $request->waktu,
            'total_harga'        => $totalHarga - $diskon,
            'diskon'             => $diskon,
            'kode'               => $kodePromo,
            'alamat_lokasi'      => $request->alamat,
            'lokasi_gmaps'       => $request->maps,
            'catatan'            => $request->catatan,
        ]);

        // Simpan detail layanan
        foreach ($layananIds as $idLayanan) {
            $harga = DB::table('layanan_subkategori')->where('id', $idLayanan)->value('harga');

            DB::table('order_detail')->insert([
                'id_order'               => $idOrder,
                'id_layanan_subkategori' => $idLayanan,
                'harga'                  => $harga,
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);
        }

        // $pelanggan = DB::table('pelanggan')->where('id_pelanggan', $idPelanggan)->first();

        // $order = DB::table('orders')->where('id_order', $idOrder)->first();

        $detailLayanan = DB::table('order_detail')
            ->join('layanan_subkategori', 'order_detail.id_layanan_subkategori', '=', 'layanan_subkategori.id')
            ->select('layanan_subkategori.nama_subkategori', 'order_detail.harga')
            ->where('id_order', $idOrder)
            ->get();

        // Kirim email invoice
        if (!empty($pelanggan->email)) {
            Mail::to($pelanggan->email)->send(new InvoiceBookingMail($pelanggan, $order, $detailLayanan));
        }

        return response()->json(['message' => 'Booking berhasil disimpan']);
    }
}
