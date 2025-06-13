<?php
namespace App\Http\Controllers;

use App\Models\LayananRootKategori;
use App\Models\LayananSubKategori;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    // Menampilkan daftar layanan
    public function index()
    {
        $rootkategori = LayananRootKategori::with('subkategori')->get();
        return view('layanan.index', compact('rootkategori'));
    }

    // Menyimpan layanan baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'layanan_rootkategori_id' => 'required|string|max:50|exists:layanan_rootkategori,id',
            'nama_subkategori'        => 'required|string|max:255',
            'harga'                   => 'required|numeric',
        ]);
        LayananSubKategori::create($validated);
        return redirect()->route('layanan.index')->with('layanan_success', 'Layanan berhasil ditambahkan!');
    }

    // Memperbarui layanan yang ada
    public function update(Request $request, $id)
    {
        // Validasi input
        $subkategori = LayananSubKategori::findOrFail($id);
        $validated   = $request->validate([
            'layanan_rootkategori_id' => 'required|string|max:50|exists:layanan_rootkategori,id',
            'nama_subkategori'        => 'required|string|max:255',
            'harga'                   => 'required|numeric',
        ]);
        $subkategori->update($validated);
        return redirect()->route('layanan.index')->with('layanan_success', 'Layanan berhasil diperbarui!');
    }

    // Menghapus layanan
    public function destroy($id)
    {
        $subkategori = LayananSubKategori::findOrFail($id);
        $subkategori->delete();
        return redirect()->route('layanan.index')
            ->with('layanan_success', 'Layanan berhasil dihapus!');
    }

    // Menyimpan kategori baru
    public function storeRootKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_rootkategori' => 'required|string|max:100|unique:layanan_rootkategori,nama_rootkategori',
        ]);
        LayananRootKategori::create($validated);
        return redirect()->route('layanan.index')->with('kategori_success', 'Kategori baru berhasil ditambahkan!');
    }

    // Menghapus kategori
    public function destroyRootKategori($id)
    {
        $kategori = LayananRootKategori::findOrFail($id);
        // Optional: Cek jika masih ada subkategori, bisa dicegah penghapusan
        if ($kategori->subkategori()->count() > 0) {
            return redirect()->route('layanan.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki subkategori.');
        }
        $kategori->delete();
        return redirect()->route('layanan.index')->with('kategori_success', 'Kategori berhasil dihapus!');
    }
}