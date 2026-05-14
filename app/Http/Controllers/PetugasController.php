<?php
namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');

            $petugasQuery = Petugas::orderBy('created_at', 'asc');

            if ($search) {
                $petugasQuery->where(function ($q) use ($search) {
                    $q->where('id_petugas', 'like', "%$search%")
                        ->orWhere('nama_petugas', 'like', "%$search%");
                });
            }

            $petugas = $petugasQuery->paginate(10)->withQueryString();

            return view('petugas.index', compact('petugas', 'search'));
        } catch (\Exception $e) {
            Log::error('Error in PetugasController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data petugas');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'no_telp'      => 'required|string|max:15|regex:/^[0-9]+$/',
        ]);

        try {
            $idPetugas = $this->generatePetugasId();

            $petugas = Petugas::create([
                'id_petugas'   => $idPetugas,
                'nama_petugas' => $validated['nama_petugas'],
                'no_telp'      => $validated['no_telp'],
                'is_available' => true,
            ]);

            return redirect()
                ->route('petugas.index')
                ->with('success', 'Petugas ' . $petugas->nama_petugas . ' berhasil ditambahkan! ID: ' . $idPetugas);

        } catch (\Exception $e) {
            Log::error('Error creating petugas: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan petugas. Silakan coba lagi.');
        }
    }

    public function update(Request $request, string $id_petugas)
    {
        $validated = $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'no_telp'      => 'required|string|max:15|regex:/^[0-9]+$/',
        ]);

        try {
            $petugas = Petugas::query()->where('id_petugas', $id_petugas)->firstOrFail();
            $petugas->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data petugas berhasil diperbarui',
                ]);
            }

            return redirect()
                ->route('petugas.index')
                ->with('success', 'Data petugas berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Error updating petugas: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data petugas',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data petugas. Silakan coba lagi.');
        }
    }

    public function destroy(Petugas $petugas)
    {
        try {
            Petugas::query()->where('id_petugas', $petugas->id_petugas)->delete();

            return redirect()
                ->route('petugas.index')
                ->with('success', 'Petugas berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting petugas: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus petugas. Silakan coba lagi.');
        }
    }

    protected function generatePetugasId(): string
    {
        $now    = Carbon::now();
        $prefix = 'PK' . $now->format('ym');

        $lastPetugas = Petugas::query()->where('id_petugas', 'like', $prefix . '%')
            ->orderBy('id_petugas', 'desc')
            ->first();

        $sequence = $lastPetugas
            ? (int) substr($lastPetugas->id_petugas, -3) + 1
            : 1;

        return $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
