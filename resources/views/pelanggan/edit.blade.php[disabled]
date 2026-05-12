@extends('layouts.app')

@section('title-content')
<h1>Detail Pelanggan</h1>
@endsection

@section('content')
<div class="detail-table">
    <!-- ID Pelanggan (readonly) -->
    <div class="row mb-1 align-items-center">
        <label class="col-md-auto col-form-label fw-semibold">ID Pelanggan :</label>
        <div class="col-md-auto">
            <input type="text" class="form-control-plaintext" value="{{ $pelanggan->id_pelanggan }}" readonly>
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" id="pelangganForm">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <!-- Nama Pelanggan -->
                <div class="row mb-2 align-items-center">
                    <label for="nama_pelanggan" class="col-md-3 col-form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                               id="nama_pelanggan" name="nama_pelanggan" 
                               value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                        @error('nama_pelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Nomor Telepon -->
                <div class="row mb-2 align-items-center">
                    <label for="nomor_telepon" class="col-md-3 col-form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" 
                                   id="nomor_telepon" name="nomor_telepon" 
                                   value="{{ old('nomor_telepon', ltrim($pelanggan->nomor_telepon, '0')) }}" 
                                   placeholder="8123456789" required>
                        </div>
                        @error('nomor_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Email -->
                <div class="row mb-2 align-items-center">
                    <label for="email" class="col-md-3 col-form-label fw-semibold">Email</label>
                    <div class="col-md-9">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email', $pelanggan->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Kota -->
                <div class="row mb-2 align-items-center">
                    <label for="kota" class="col-md-3 col-form-label fw-semibold">Kota <span class="text-danger"></span></label>
                    <div class="col-md-9">
                        <select class="form-select select2-kota @error('kota') is-invalid @enderror" 
                                id="kota" name="kota">
                            <option value="{{ $pelanggan->kota }}" selected>{{ $pelanggan->kota }}</option>
                        </select>
                        @error('kota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="row mb-2 align-items-center">
                    <label for="alamat" class="col-md-3 col-form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                    <div class="col-md-9">
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Google Maps -->
                <div class="row mb-2 align-items-center">
                    <label for="gmaps" class="col-md-3 col-form-label fw-semibold">Google Maps</label>
                    <div class="col-md-9">
                        <div class="input-group mb-2">
                            <input type="url" class="form-control @error('gmaps') is-invalid @enderror" 
                                id="gmaps" name="gmaps" 
                                value="{{ old('gmaps', $pelanggan->gmaps) }}" 
                                placeholder="https://maps.google.com/...">
                            @error('gmaps')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(old('gmaps', $pelanggan->gmaps))
                        <a href="{{ old('gmaps', $pelanggan->gmaps) }}" 
                        class="btn btn-link p-0" 
                        target="_blank">
                            Lihat Lokasi
                        </a>
                        @endif
                    </div>
                </div>

                
                <!-- Patokan -->
                <div class="row mb-2 align-items-center">
                    <label for="patokan" class="col-md-3 col-form-label fw-semibold">Patokan</label>
                    <div class="col-md-9">
                        <textarea class="form-control @error('patokan') is-invalid @enderror" 
                                  id="patokan" name="patokan" rows="2">{{ old('patokan', $pelanggan->patokan) }}</textarea>
                        @error('patokan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: Depan masjid, sebelah minimarket, dll.</small>
                    </div>
                </div>
            </div>
            
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('pelanggan.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn-add">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for city search
    $('.select2-kota').select2({
        placeholder: "Pilih/Cari Kota...",
        allowClear: true,
        width: '100%',
        ajax: {
            url: '/api/kota', // You should create this endpoint
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.name,
                            id: item.name
                        }
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    // Format phone number input
    $('#nomor_telepon').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Form validation
    $('#pelangganForm').validate({
        rules: {
            nama_pelanggan: {
                required: true,
                minlength: 3
            },
            nomor_telepon: {
                required: true,
                minlength: 9,
                maxlength: 13,
                digits: true
            },
            email: {
                email: true
            },
            kota: "required",
            alamat: "required",
            gmaps: {
                url: true
            }
        },
        messages: {
            nama_pelanggan: {
                required: "Harap isi nama pelanggan",
                minlength: "Nama minimal 3 karakter"
            },
            nomor_telepon: {
                required: "Harap isi nomor telepon",
                minlength: "Nomor telepon minimal 9 digit",
                maxlength: "Nomor telepon maksimal 13 digit",
                digits: "Hanya boleh berisi angka"
            },
            email: {
                email: "Format email tidak valid"
            },
            kota: "Harap pilih kota",
            alamat: "Harap isi alamat",
            gmaps: {
                url: "Masukkan URL Google Maps yang valid"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('.select2-container'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            if ($(element).hasClass('select2-hidden-accessible')) {
                $(element).next('.select2-container').addClass('is-invalid');
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            if ($(element).hasClass('select2-hidden-accessible')) {
                $(element).next('.select2-container').removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            // Format phone number before submit
            let phone = $('#nomor_telepon').val();
            if (phone && !phone.startsWith('0')) {
                $('#nomor_telepon').val('0' + phone);
            }
            form.submit();
        }
    });
});
</script>
@endpush
