<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = Pelanggan::with('kota')->orderBy('created_at', 'asc')->paginate(10);
        $kotas = Kota::orderBy('nama_kota')->get();
        return view('pelanggan.index', compact('pelanggans', 'kotas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create', [
            'id_pelanggan' => $this->generatePelangganId(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi lebih eksplisit
    $validator = Validator::make($request->all(), [
        'nama_pelanggan' => 'required|string|min:3|max:100',
        'telp_pelanggan' => 'required|unique:pelanggan,telp_pelanggan',
        'email'          => 'nullable|email|unique:pelanggan,email',
        'id_kota'        => 'required|exists:kota,id_kota',
        'alamat_lokasi'  => 'required|string|max:255',
        'lokasi_gmaps'   => 'nullable|url|max:500',
        'catatan'        => 'nullable|string|max:255'
    ], [
        'telp_pelanggan.unique' => 'Nomor telepon sudah terdaftar',
        'email.unique' => 'Email sudah terdaftar',
    ]);

    if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $validated = $validator->validated();
        $validated['id_pelanggan'] = $this->generatePelangganId();
        
        Pelanggan::create($validated);
        
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
 * Generate unique Pelanggan ID in format CSYYMMNNN
 */
protected function generatePelangganId(): string
{
    $now = Carbon::now();
    $prefix = 'CS' . $now->format('ym'); // Tahun 2 digit dan Bulan
    
    // Cari ID terakhir dengan prefix bulan ini
    $lastPelanggan = Pelanggan::where('id_pelanggan', 'like', $prefix.'%')
        ->orderBy('id_pelanggan', 'desc')
        ->first();

    // Jika ada, ambil nomor urut terakhir + 1
    if ($lastPelanggan) {
        $lastSequence = (int) substr($lastPelanggan->id_pelanggan, -3);
        $sequence = $lastSequence + 1;
    } else {
        // Jika tidak ada, mulai dari 001
        $sequence = 1;
    }

    // Format nomor urut 3 digit
    $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);
    
    return $prefix . $sequenceFormatted;
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $pelanggans = Pelanggan::findOrFail($id);
            return view('pelanggan.index', compact('pelanggan'));
        } catch (\Exception $e) {
            Log::error('Error showing pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Pelanggan tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $pelanggans = Pelanggan::findOrFail($id);
            return view('pelanggan.edit', compact('pelanggan'));
        } catch (\Exception $e) {
            Log::error('Error editing pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Pelanggan tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|min:3|max:100',
            'telp_pelanggan' => [
                'required',
                Rule::unique('pelanggan', 'telp_pelanggan')->ignore($id, 'id_pelanggan')
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('pelanggan', 'email')->ignore($id, 'id_pelanggan')
            ],
            'id_kota'        => 'required|exists:kota,id_kota',
            'alamat_lokasi'  => 'required|string|max:255',
            'lokasi_gmaps'   => 'nullable|url|max:500',
            'catatan'        => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->update($validator->validated());

            return redirect()->route('pelanggan.index')
                ->with('success', 'Data pelanggan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data pelanggan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pelanggans = Pelanggan::findOrFail($id);
            $pelanggans->delete();

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pelanggan.');
        }
    }
}

