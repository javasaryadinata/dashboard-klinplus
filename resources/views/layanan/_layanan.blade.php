<div class="mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahLayananModal">Tambah Layanan</button>
</div>
@if(session('layanan_success'))
    <div class="alert alert-success alert-dismissible fade show" id="layananAlert">
        {{ session('layanan_success') }}
    </div>
@endif
@if(session('layanan_error'))
    <div class="alert alert-danger alert-dismissible fade show" id="layananAlert">
        {{ session('layanan_error') }}
    </div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Kategori</th>
            <th>Nama Layanan</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach($rootkategori as $root)
            @foreach($root->subkategori as $sub)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $root->nama_rootkategori }}</td>
                <td>{{ $sub->nama_subkategori }}</td>
                <td>Rp {{ number_format($sub->harga, 0, ',', '.') }}</td>
                <td>
                    <!-- Edit & Delete Button -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editLayananModal{{ $sub->id }}">Edit</button>
                    <form action="{{ route('layanan.destroy', $sub->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<!-- Modal Tambah Layanan -->
<div class="modal fade" id="tambahLayananModal" tabindex="-1" aria-labelledby="tambahLayananModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('layanan.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLayananModalLabel">Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="layanan_rootkategori_id" class="form-label">Kategori</label>
                        <select class="form-control" name="layanan_rootkategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($rootkategori as $root)
                                <option value="{{ $root->id }}">{{ $root->nama_rootkategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_subkategori" class="form-label">Nama Layanan</label>
                        <input type="text" class="form-control" name="nama_subkategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Layanan -->
@foreach($rootkategori as $root)
    @foreach($root->subkategori as $sub)
    <div class="modal fade" id="editLayananModal{{ $sub->id }}" tabindex="-1" aria-labelledby="editLayananModalLabel{{ $sub->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('layanan.update', $sub->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLayananModalLabel{{ $sub->id }}">Edit Layanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="layanan_rootkategori_id" class="form-label">Kategori</label>
                            <select class="form-control" name="layanan_rootkategori_id" required>
                                @foreach($rootkategori as $r)
                                    <option value="{{ $r->id }}" {{ $sub->layanan_rootkategori_id == $r->id ? 'selected' : '' }}>{{ $r->nama_rootkategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_subkategori" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" name="nama_subkategori" value="{{ $sub->nama_subkategori }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" name="harga" value="{{ $sub->harga }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach
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