<?php
namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->query('search');
        $filterKota = $request->query('filter_kota');
        $sort       = $request->query('sort', 'desc');

        $kotas = Kota::query()->orderBy('nama_kota', 'asc')->get();

        $pelanggansQuery = Pelanggan::with('kota');

        if ($search) {
            $pelanggansQuery->where(function ($q) use ($search) {
                $q->where('id_pelanggan', 'like', "%$search%")
                    ->orWhere('nama_pelanggan', 'like', "%$search%");
            });
        }

        if ($filterKota) {
            $pelanggansQuery->where('id_kota', $filterKota);
        }

        $pelanggans = $pelanggansQuery->paginate(10)->appends($request->all());

        return view('pelanggan.index', compact('pelanggans', 'kotas', 'search', 'filterKota', 'sort'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|min:3|max:100',
            'telp_pelanggan' => 'required|unique:pelanggan,telp_pelanggan',
            'email'          => 'nullable|email|unique:pelanggan,email',
            'id_kota'        => 'required|exists:kota,id_kota',
            'alamat_lokasi'  => 'required|string|max:255',
            'lokasi_gmaps'   => 'nullable|url|max:500',
            'catatan'        => 'nullable|string|max:255',
        ], [
            'telp_pelanggan.unique' => 'Nomor telepon sudah terdaftar',
            'email.unique'          => 'Email sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $validated                 = $validator->validated();
            $validated['id_pelanggan'] = $this->generatePelangganId();

            Pelanggan::create($validated);

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function generatePelangganId(): string
    {
        $now    = Carbon::now();
        $prefix = 'CS' . $now->format('ym');

        $lastPelanggan = Pelanggan::query()->where('id_pelanggan', 'like', $prefix . '%')
            ->orderBy('id_pelanggan', 'desc')
            ->first();

        if ($lastPelanggan) {
            $lastSequence = (int) substr($lastPelanggan->id_pelanggan, -3);
            $sequence     = $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        $sequenceFormatted = str_pad($sequence, 3, '0', STR_PAD_LEFT);

        return $prefix . $sequenceFormatted;
    }

    public function show(string $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            return view('pelanggan.index', compact('pelanggan'));
        } catch (\Exception $e) {
            Log::error('Error showing pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Pelanggan tidak ditemukan.');
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|min:3|max:100',
            'telp_pelanggan' => [
                'required',
                Rule::unique('pelanggan', 'telp_pelanggan')->ignore($id, 'id_pelanggan'),
            ],
            'email'          => [
                'nullable',
                'email',
                Rule::unique('pelanggan', 'email')->ignore($id, 'id_pelanggan'),
            ],
            'id_kota'        => 'required|exists:kota,id_kota',
            'alamat_lokasi'  => 'required|string|max:255',
            'lokasi_gmaps'   => 'nullable|url|max:500',
            'catatan'        => 'nullable|string|max:255',
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
