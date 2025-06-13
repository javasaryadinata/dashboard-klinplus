<div class="mb-3">
    <form method="POST" action="{{ route('layanan.kategori.store') }}" class="d-flex gap-2">
        @csrf
        <input type="text" name="nama_rootkategori" class="form-control" placeholder="Nama Kategori Baru" required>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
</div>
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
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
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
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kategori?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
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