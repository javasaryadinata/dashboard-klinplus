<?php
namespace App\Http\Controllers;

use App\Models\LayananRootKategori;
use App\Models\LayananSubKategori;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $active_tab = $request->query('active_tab', $search ? 'layanan' : 'kategori');

        $all_categories = LayananRootKategori::with('subkategori')->get();

        $rootkategori = LayananRootKategori::with(['subkategori' => function($query) use ($search) {
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_subkategori', 'like', "%{$search}%")
                    ->orWhereHas('rootKategori', function($rq) use ($search) {
                        $rq->where('nama_rootkategori', 'like', "%{$search}%");
                    });
                });
            }
        }])
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_rootkategori', 'like', "%{$search}%")
                ->orWhereHas('subkategori', function($sq) use ($search) {
                    $sq->where('nama_subkategori', 'like', "%{$search}%");
                });
            });
        })
        ->get();        

        return view('layanan.index', compact('rootkategori', 'all_categories', 'search', 'active_tab'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_rootkategori_id' => 'required|string|max:50|exists:layanan_rootkategori,id',
            'nama_subkategori'        => 'required|string|max:255',
            'harga'                   => 'required|numeric',
        ]);
        LayananSubKategori::create($validated);
        return redirect()->route('layanan.index')->with('layanan_success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $subkategori = LayananSubKategori::findOrFail($id);
        $validated   = $request->validate([
            'layanan_rootkategori_id' => 'required|string|max:50|exists:layanan_rootkategori,id',
            'nama_subkategori'        => 'required|string|max:255',
            'harga'                   => 'required|numeric',
        ]);
        $subkategori->update($validated);
        return redirect()->route('layanan.index')->with('layanan_success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $subkategori = LayananSubKategori::findOrFail($id);
        $subkategori->delete();
        return redirect()->route('layanan.index')
            ->with('layanan_success', 'Layanan berhasil dihapus!');
    }

    public function storeRootKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_rootkategori' => 'required|string|max:100|unique:layanan_rootkategori,nama_rootkategori',
        ]);
        LayananRootKategori::create($validated);
        return redirect()->route('layanan.index')->with('kategori_success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroyRootKategori(string $id)
    {
        $kategori = LayananRootKategori::findOrFail($id);
        if ($kategori->subkategori()->count() > 0) {
            return redirect()->route('layanan.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki subkategori.');
        }
        $kategori->delete();
        return redirect()->route('layanan.index')->with('kategori_success', 'Kategori berhasil dihapus!');
    }
}