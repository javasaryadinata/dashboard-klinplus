@if(session('kategori_success'))
    <div class="alert alert-success alert-dismissible fade show" id="kategoriAlert">
        {{ session('kategori_success') }}
    </div>
@endif
@if(session('kategori_error'))
    <div class="alert alert-danger alert-dismissible fade show" id="kategoriAlert">
        {{ session('kategori_error') }}
    </div>
@endif
<div class="d-flex mb-2">
    <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
        Tambah Kategori Layanan
    </button>
</div>
<table class="layanan-kategori-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rootkategori as $i => $root)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $root->nama_rootkategori }}</td>
            <td>
                <form action="{{ route('layanan.kategori.destroy', $root->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-hapus" onclick="return confirm('Yakin hapus kategori?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('layanan.kategori.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="tambahKategoriLabel">Tambah Kategori Layanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_rootkategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_rootkategori" name="nama_rootkategori" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn-add">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
    setTimeout(function() {
        let kategoriAlert = document.getElementById('kategoriAlert');
        if(kategoriAlert) kategoriAlert.style.display = 'none';
        let layananAlert = document.getElementById('layananAlert');
        if(layananAlert) layananAlert.style.display = 'none';
    }, 3000); // 3 detik
</script>
@endpush