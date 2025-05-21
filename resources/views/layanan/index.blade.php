@extends('layouts.app')

@section('title-content')
    <h1>Layanan</h1>
@endsection

@section('content')
    <div class="container">
        <div class="btn-layanan">
            <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahLayananModal">
                Tambah Layanan
            </button>
        </div>
    </div>

    <div class="container-table">
        <div class="table-wrapper">
            <table class="pricelist-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Pricelist</th>
                        <th>Nama Layanan</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanans as $layanan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $layanan->id_pricelist }}</td>
                            <td>{{ $layanan->nama_layanan }}</td>
                            <td>{{ $layanan->durasi }} menit</td>
                            <td>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                            <td>{{ Str::limit($layanan->deskripsi, 50) }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="edit-button" data-bs-toggle="modal" 
                                        data-bs-target="#editLayananModal{{ $layanan->id_pricelist }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('layanan.destroy', $layanan->id_pricelist) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data layanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Layanan -->
    <div class="modal fade" id="tambahLayananModal" tabindex="-1" aria-labelledby="tambahLayananModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="tambahLayananModalLabel">Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('layanan.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_pricelist" class="form-label">ID Layanan</label>
                            <input type="text" class="form-control" id="id_pricelist" name="id_pricelist" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_layanan" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="nama_layanan" name="nama_layanan" required>
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (menit)</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
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
    @foreach($layanans as $layanan)
        <div class="modal fade" id="editLayananModal{{ $layanan->id_pricelist }}" tabindex="-1" 
             aria-labelledby="editLayananModalLabel{{ $layanan->id_pricelist}}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-white text-dark">
                        <h5 class="modal-title" id="editLayananModalLabel{{ $layanan->id_pricelist }}">Edit Layanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('layanan.update', $layanan->id_pricelist) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_id_pricelist{{ $layanan->id_pricelist }}" class="form-label">ID Layanan</label>
                                <input type="text" class="form-control" id="edit_id_pricelist{{ $layanan->id_pricelist }}" 
                                       name="id_pricelist" value="{{ $layanan->id_pricelist }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nama_layanan{{ $layanan->id_pricelist }}" class="form-label">Nama Layanan</label>
                                <input type="text" class="form-control" id="edit_nama_layanan{{ $layanan->id_pricelist }}" 
                                       name="nama_layanan" value="{{ $layanan->nama_layanan }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_durasi{{ $layanan->id_pricelist }}" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="edit_durasi{{ $layanan->id_pricelist }}" 
                                       name="durasi" value="{{ $layanan->durasi }}" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="edit_harga{{ $layanan->id_pricelist }}" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="edit_harga{{ $layanan->id_pricelist }}" 
                                       name="harga" value="{{ $layanan->harga }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi{{ $layanan->id_pricelist }}" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi{{ $layanan->id_pricelist }}" 
                                          name="deskripsi" rows="3" required>{{ $layanan->deskripsi }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-add">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Show success/error messages
            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif
            @if($errors->any())
                toastr.error('{{ $errors->first() }}');
            @endif
        });
    </script>
@endpush
