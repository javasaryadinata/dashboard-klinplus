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
<div class="d-flex justify-content-between align-items-center style="gap:16px;">
    <form action="{{ route('layanan.index') }}" method="GET" class="d-flex align-items-center" autocomplete="off" style="flex:1;">
        <input type="hidden" name="active_tab" value="layanan">
        <div class="input-group me-2" style="max-width:400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari" value="{{ $search }}">
            @if($search)
                <a href="{{ route('layanan.index') }}?active_tab=layanan" class="btn-clear-search" id="btn-clear-search">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
    <button class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahLayananModal">Tambah Layanan</button>
</div>
<div class="container-table">
    <div class="table-wrapper">
        <table class="layanan-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $i = 1; 
                    $hasResults = false; // Penanda apakah ada layanan yang tampil
                @endphp
                @foreach($rootkategori as $root)
                    {{-- Hanya tampilkan baris jika subkategori ada isinya (setelah difilter) --}}
                    @foreach($root->subkategori as $sub)
                        @php $hasResults = true; @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $root->nama_rootkategori }}</td>
                            <td>{{ $sub->nama_subkategori }}</td>
                            <td>Rp {{ number_format($sub->harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="action-buttons gap-2">
                                    <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#editLayananModal{{ $sub->id }}">Edit</button>
                                    <form action="{{ route('layanan.destroy', $sub->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-hapus" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                @if(!$hasResults)
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <strong>Layanan "{{ $search }}" tidak ditemukan.</strong>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

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
                    <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-add">Tambahkan</button>
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
                        <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
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