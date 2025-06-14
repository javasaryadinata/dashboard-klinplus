@extends('layouts.app')

@section('title-content')
<h1>Petugas</h1>
@endsection

@section('content')
<div class="container">
    {{-- <div class="btn-petugas">
        <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahPetugasModal">
            Tambah Petugas
        </button>
    </div> --}}
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPetugasModal">Tambah Petugas</button>
    </div>
</div>
<div class="container-table">
    <div class="table-wrapper">
        <table class="staf-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Id Petugas</th>
                    <th>Nama Petugas</th>
                    <th>Nomor Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petugas as $ptg)
                    <tr>
                       <td>{{ $loop->iteration }}</td>
                       <td>
                           @if($ptg->is_available)
                               <span class="badge bg-success text-white">Available</span>
                           @else
                               <span class="badge bg-warning text-white">Booked</span>
                           @endif
                       </td>
                       <td>{{ $ptg->id_petugas }}</td>
                       <td>{{ $ptg->nama_petugas }}</td>
                       <td>{{ $ptg->no_telp }}</td>
                       <td>
                        <div class="d-flex gap-2">
                            <button type="button" class="edit-button" data-bs-toggle="modal" data-bs-target="#editPetugasModal{{ $ptg->id_petugas }}">Edit</button>
                            <form action="{{ route('petugas.destroy', $ptg->id_petugas) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">Hapus</button>
                            </form>
                        </div>
                       </td>
                    </tr>

                    <!-- Edit Petugas Modal -->
                    <div class="modal fade" id="editPetugasModal{{ $ptg->id_petugas }}" tabindex="-1" aria-labelledby="editPetugasModalLabel{{ $ptg->id_petugas }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-white text-dark">
                                    <h5 class="modal-title" id="editPetugasModalLabel{{ $ptg->id_petugas }}">Edit Petugas</h5>
                                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('petugas.update', $ptg->id_petugas) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="edit_nama_petugas_{{ $ptg->id_petugas }}" class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="edit_nama_petugas_{{ $ptg->id_petugas }}" name="nama_petugas" value="{{ $ptg->nama_petugas }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_no_telp_{{ $ptg->id_petugas }}" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="edit_no_telp_{{ $ptg->id_petugas }}" name="no_telp" 
                                                   pattern="[0-9]{10,15}" value="{{ $ptg->no_telp }}" required>
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data petugas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tambah Petugas Modal -->
    <div class="modal fade" id="tambahPetugasModal" tabindex="-1" aria-labelledby="tambahPetugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="tambahPetugasModalLabel">Tambah Petugas</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('petugas.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_petugas" class="form-label">Nama Petugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="no_telp" name="no_telp" 
                                   pattern="[0-9]{10,15}" required>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Format nomor telepon input
        $('#no_telp, [id^="edit_no_telp_"]').on('input', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
    });
</script>
@endpush
