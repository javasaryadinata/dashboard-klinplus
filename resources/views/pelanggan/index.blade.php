@extends('layouts.app')

@section('title-content')
<h1>Pelanggan</h1>
@endsection

@section('content')
<div class="container-btn-tambah mb-2">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahPelangganModal">
            Tambah Pelanggan
        </button>
    </div>
</div>
<div class="d-flex align-items-center justify-content-between" style="gap:16px;">
    <form method="GET" action="{{ route('pelanggan.index') }}" class="d-flex align-items-center flex-grow-1" autocomplete="off" style="max-width: 400px;">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Cari" value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('pelanggan.index') }}" class="btn-clear-search" id="btn-clear-search">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
    <div class="d-flex align-items-center ms-3">
        <span class="me-2">Filter</span>
        <form method="GET" action="{{ route('pelanggan.index') }}" class="mb-0">
            <select class="form-select" name="filter_kota" onchange="this.form.submit()" style="width:auto; min-width: 120px;">
                <option value="">Semua</option>
                @foreach($kotas as $kota)
                    <option value="{{ $kota->id_kota }}" {{ request('filter_kota') == $kota->id_kota ? 'selected' : '' }}>
                        {{ $kota->nama_kota }}
                    </option>
                @endforeach
            </select>
            {{-- Kirim search juga agar filter & search bisa bersamaan --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
        </form>
    </div>
</div>


<div class="container-table">
    <div class="table-wrapper">
        <table class="customer-table">
            <thead>
                <tr>
                    <th>No</th>
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
                        <div class="action-buttons gap-2">
                            <button type="button"
                                class="btn btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editPelangganModal{{ $pelanggan->id_pelanggan }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('pelanggan.destroy', $pelanggan->id_pelanggan) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-hapus" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
                                        <label for="edit_telp_pelanggan_{{ $pelanggan->id_pelanggan }}" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_telp_pelanggan_{{ $pelanggan->id_pelanggan }}" 
                                               name="telp_pelanggan" value="{{ $pelanggan->telp_pelanggan }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_email_{{ $pelanggan->id_pelanggan }}" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit_email_{{ $pelanggan->id_pelanggan }}" 
                                               name="email" value="{{ $pelanggan->email }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_kota" class="form-label">Kota <span class="text-danger">*</span></label>
                                        <select name="id_kota" id="id_kota" class="form-control @error('id_kota') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kota --</option>
                                            @foreach($kotas as $kota)
                                                 <option value="{{ $kota->id_kota }}"
                                                    {{ (old('id_kota', $pelanggan->id_kota) == $kota->id_kota) ? 'selected' : '' }}>
                                                    {{ $kota->nama_kota }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_alamat_lokasi_{{ $pelanggan->id_pelanggan }}" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="edit_alamat_lokasi_{{ $pelanggan->id_pelanggan }}" 
                                                  name="alamat_lokasi" rows="3">{{ $pelanggan->alamat_lokasi }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="edit_lokasi_gmaps_{{ $pelanggan->id_pelanggan }}" class="form-label">Link Google Maps</label>
                                        <input type="url" class="form-control" id="edit_lokasi_gmaps_{{ $pelanggan->id_pelanggan }}" 
                                               name="lokasi_gmaps" placeholder="https://maps.google.com/..."
                                               value="{{ $pelanggan->lokasi_gmaps }}">
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
                        <label for="telp_pelanggan" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('telp_pelanggan') is-invalid @enderror" 
                               id="telp_pelanggan" name="telp_pelanggan" 
                               value="{{ old('telp_pelanggan') }}" required>
                        @error('telp_pelanggan')
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

                    <div class="mb-3">
                        <label for="id_kota" class="form-label">Kota <span class="text-danger">*</span></label>
                        <select name="id_kota" id="id_kota" class="form-control @error('id_kota') is-invalid @enderror" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($kotas as $kota)
                                <option value="{{ $kota->id_kota }}" {{ old('id_kota') == $kota->id_kota ? 'selected' : '' }}>
                                    {{ $kota->nama_kota }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat_lokasi" class="form-label">Alamat</label>
                        <textarea class="form-control @error('alamat_lokasi') is-invalid @enderror" 
                                  id="alamat_lokasi" name="alamat_lokasi" rows="3" required>{{ old('alamat_lokasi') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi_gmaps" class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control @error('lokasi_gmaps') is-invalid @enderror" 
                               id="lokasi_gmaps" name="lokasi_gmaps" placeholder="https://maps.google.com/..."
                               value="{{ old('lokasi_gmaps') }}">
                        @error('lokasi_gmaps')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror"
                                id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                        @error('catatan')
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
            telp_pelanggan: {
                required: true,
                minlength: 10,
                maxlength: 15,
                digits: true
            },
            email: {
                email: true
            },
            lokasi_gmaps: {
                url: true
            }
        },
        messages: {
            nama_pelanggan: "Harap isi nama pelanggan",
            telp_pelanggan: {
                required: "Harap isi nomor telepon",
                minlength: "Nomor telepon minimal 10 digit",
                maxlength: "Nomor telepon maksimal 15 digit",
                digits: "Hanya boleh berisi angka"
            },
            email: {
                email: "Format email tidak valid"
            },
            lokasi_gmaps: {
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
            telp_pelanggan: {
                required: true,
                minlength: 10,
                maxlength: 15,
                digits: true
            },
            email: {
                email: true
            },
            lokasi_gmaps: {
                url: true
            }
        },
        messages: {
            nama_pelanggan: "Harap isi nama pelanggan",
            telp_pelanggan: {
                required: "Harap isi nomor telepon",
                minlength: "Nomor telepon minimal 10 digit",
                maxlength: "Nomor telepon maksimal 15 digit",
                digits: "Hanya boleh berisi angka"
            },
            email: {
                email: "Format email tidak valid"
            },
            lokasi_gmaps: {
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


