@extends('layouts.app')

@section('title-content')
<h1>Pelanggan</h1>
@endsection

@section('content')
<div class="container">
    <div class="btn-pelanggan">
        <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahPelangganModal">
            Tambah Pelanggan
        </button>
    </div>
</div>

<div class="container-table">
    <div class="table-wrapper">
        <table class="customer-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>No. Telepon</th>
                    <th>Email</th>
                    <th>Kota</th>
                    <th>Alamat</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggans as $pelanggan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pelanggan->id_pelanggan ?? 'CS000000' }}</td>
                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                    <td>{{ $pelanggan->telp_pelanggan }}</td>
                    <td>{{ $pelanggan->email ?? '-' }}</td>
                    <td>{{ $pelanggan->kota->nama_kota ?? '-' }}</td>
                    <td>{{ Str::limit($pelanggan->alamat_lokasi, 30) }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}" class="edit-button">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('pelanggan.destroy', $pelanggan->id_pelanggan) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Edit Pelanggan Modal -->
                <div class="modal fade" id="editPelangganModal{{ $pelanggan->id_pelanggan }}" tabindex="-1" aria-labelledby="editPelangganModalLabel{{ $pelanggan->id_pelanggan }}" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header bg-white text-dark">
                                <h5 class="modal-title" id="editPelangganModalLabel{{ $pelanggan->id_pelanggan }}">Edit Pelanggan</h5>
                                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="edit_nama_pelanggan_{{ $pelanggan->id_pelanggan }}" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_nama_pelanggan_{{ $pelanggan->id_pelanggan }}" 
                                               name="nama_pelanggan" value="{{ $pelanggan->nama_pelanggan }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_nomor_telepon_{{ $pelanggan->id_pelanggan }}" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_nomor_telepon_{{ $pelanggan->id_pelanggan }}" 
                                               name="nomor_telepon" value="{{ $pelanggan->nomor_telepon }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_email_{{ $pelanggan->id_pelanggan }}" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit_email_{{ $pelanggan->id_pelanggan }}" 
                                               name="email" value="{{ $pelanggan->email }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_alamat_{{ $pelanggan->id_pelanggan }}" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="edit_alamat_{{ $pelanggan->id_pelanggan }}" 
                                                  name="alamat" rows="3">{{ $pelanggan->alamat }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_gmaps_{{ $pelanggan->id_pelanggan }}" class="form-label">Link Google Maps</label>
                                        <input type="url" class="form-control" id="edit_gmaps_{{ $pelanggan->id_pelanggan }}" 
                                               name="gmaps" placeholder="https://maps.google.com/..."
                                               value="{{ $pelanggan->gmaps }}">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn-add">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pelanggan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pelanggans->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $pelanggans->links() }}
        </div>
    @endif
</div>

<!-- Tambah Pelanggan Modal -->
<div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="tambahPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h5 class="modal-title" id="tambahPelangganModalLabel">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahPelanggan" method="POST" action="{{ route('pelanggan.store') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                               id="nama_pelanggan" name="nama_pelanggan" 
                               value="{{ old('nama_pelanggan') }}" required>
                        @error('nama_pelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" 
                               id="nomor_telepon" name="nomor_telepon" 
                               value="{{ old('nomor_telepon') }}" required>
                        @error('nomor_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="id_kota" class="block text-gray-700">Kota</label>
                        <select name="id_kota" id="id_kota" class="w-full px-3 py-2 border rounded-lg" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($kotas as $kota)
                                <option value="{{ $kota->id_kota }}">{{ $kota->nama_kota }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gmaps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control @error('gmaps') is-invalid @enderror" 
                               id="gmaps" name="gmaps" placeholder="https://maps.google.com/..."
                               value="{{ old('gmaps') }}">
                        @error('gmaps')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-add">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format nomor telepon
    $('#nomor_telepon, [id^="edit_nomor_telepon_"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Validasi form sebelum submit
    $('#formTambahPelanggan').validate({
        rules: {
            nama_pelanggan: "required",
            nomor_telepon: {
                required: true,
                minlength: 10,
                maxlength: 15,
                digits: true
            },
            email: {
                email: true
            },
            gmaps: {
                url: true
            }
        },
        messages: {
            nama_pelanggan: "Harap isi nama pelanggan",
            nomor_telepon: {
                required: "Harap isi nomor telepon",
                minlength: "Nomor telepon minimal 10 digit",
                maxlength: "Nomor telepon maksimal 15 digit",
                digits: "Hanya boleh berisi angka"
            },
            email: {
                email: "Format email tidak valid"
            },
            gmaps: {
                url: "Masukkan URL yang valid"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    // Validasi untuk form edit
    $('[id^="editPelangganModal"] form').validate({
        rules: {
            nama_pelanggan: "required",
            nomor_telepon: {
                required: true,
                minlength: 10,
                maxlength: 15,
                digits: true
            },
            email: {
                email: true
            },
            gmaps: {
                url: true
            }
        },
        messages: {
            nama_pelanggan: "Harap isi nama pelanggan",
            nomor_telepon: {
                required: "Harap isi nomor telepon",
                minlength: "Nomor telepon minimal 10 digit",
                maxlength: "Nomor telepon maksimal 15 digit",
                digits: "Hanya boleh berisi angka"
            },
            email: {
                email: "Format email tidak valid"
            },
            gmaps: {
                url: "Masukkan URL yang valid"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    // Reset form saat modal tambah ditutup
    $('#tambahPelangganModal').on('hidden.bs.modal', function () {
        $('#formTambahPelanggan')[0].reset();
        $('#formTambahPelanggan').validate().resetForm();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });
});
</script>
@endpush


