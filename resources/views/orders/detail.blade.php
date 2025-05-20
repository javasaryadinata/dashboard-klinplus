@extends('layouts.app')

@section('title-content')
<h1>Detail Order</h1>
@endsection

@section('content')

<div class="detail-table">
    <!-- Customer Information Section -->
    <div class="row align-items-center mb-3">
        <label class="col-md-2 col-form-label fw-semibold text-dark">ID Order</label>
        <div class="col-md-10">
            <input type="text" class="form-control bg-light text-dark" value="{{ $order->id_order }}" readonly>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <label class="col-md-2 col-form-label fw-semibold text-dark">Nama Pelanggan :</label>
        <div class="col-md-10">
            <input type="text" class="form-control bg-light text-dark" value="{{ $order->pelanggan->nama_pelanggan }}" readonly>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <label class="col-md-2 col-form-label fw-semibold text-dark">Alamat :</label>
        <div class="col-md-10">
            <input type="text" class="form-control bg-light text-dark" value="{{ $order->pelanggan->alamat ?? '-' }}" readonly>
        </div>
    </div>

    <!-- Cleaning Schedule Section -->
    <div class="row align-items-center mb-3">
        <label class="col-md-2 col-form-label fw-semibold text-dark">Tanggal Pembersihan :</label>
        <div class="col-md-10">
            <input type="text" class="form-control bg-light text-dark" 
                   value="{{ $order->tanggal_pembersihan }} {{ $order->waktu_pembersihan }}" readonly>
        </div>
    </div>

    <div class="row align-items-center mb-3">
        <label class="col-md-2 col-form-label fw-semibold text-dark">Jam Pembersihan :</label>
        <div class="col-md-10">
            <input type="text" class="form-control bg-light text-dark" 
                   value="{{ \Carbon\Carbon::parse($order->waktu_pembersihan)->format('H:i') }}" readonly>
        </div>
    </div>
</div>

<hr>

<!-- Services Section -->

<form method="POST" action="{{ route('orders.updateLayanan', $order->id_order) }}">
    @csrf
    <div class="btn-detail-layanan">
        <button type="button" class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahOrderLayananModal">
            Tambah Layanan
        </button>
    </div>

    <div class="container-table">
        <div class="table-wrapper">
            <table class="table" id="layananOrderTable">
                <thead class="table-light">
                    <tr>
                        <th>Kode Layanan</th>
                        <th>Nama Layanan</th>
                        <th>Estimasi Selesai</th>
                        <th>Nama Petugas</th>
                        <th>Sub Total</th>
                        <th>Action</th>
                    </tr>  
                </thead>
                <tbody>
                    @foreach($order->layanans as $layanan)
                    <tr data-layanan-id="{{ $layanan->id_layanan }}">
                        <td>{{ $layanan->pricelist->id_pricelist }}</td>
                        <td>{{ $layanan->pricelist->nama_layanan ?? $layanan->nama_layanan }}</td>
                        <td>{{ $layanan->pivot->estimasi_selesai }}</td>
                        <td>
                            @if($layanan->pivot->petugas)
                                {{ $layanan->pivot->petugas }} - {{ $layanan->pivot->nama_petugas }}
                            @else
                                -
                            @endif
                        </td>
                        <td>Rp{{ number_format($layanan->pivot->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info btn-edit-petugas" 
                                data-layanan-id="{{ $layanan->id_layanan }}"
                                data-current-petugas="{{ $layanan->pivot->petugas ?? '' }}"
                                data-current-nama-petugas="{{ $layanan->pivot->nama_petugas ?? '' }}">
                                Edit Petugas
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hidden inputs container dengan data awal -->
    <div id="hiddenInputsContainer">
        @foreach($order->layanans as $layanan)
        <div class="hidden-input-wrapper" data-layanan-id="{{ $layanan->id_layanan }}">
            <input type="hidden" name="layanans[]" value="{{ $layanan->id_layanan }}">
            <input type="hidden" name="subtotals[]" value="{{ $layanan->pivot->subtotal }}">
            <input type="hidden" name="estimasi_selesais[]" value="{{ $layanan->pivot->estimasi_selesai }}">
            <input type="hidden" name="petugas[]" value="{{ $layanan->pivot->petugas ?? '' }}">
            <input type="hidden" name="nama_petugas[]" value="{{ $layanan->pivot->nama_petugas ?? '' }}">
        </div>
        @endforeach
    </div>

    <!-- Order Summary Section -->
    <div class="detail-table">
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Estimasi (Menit) :</label>
            <div class="col-md-10">
                <input type="text" class="form-control bg-light text-dark"
                    value="{{ $order->layanans->sum('durasi') }}" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Jam Selesai :</label>
            <div class="col-md-10">
                @php
                    $totalDurasi = $order->layanans->sum('durasi');
                    $jamMulai = \Carbon\Carbon::parse($order->waktu_pembersihan);
                    $jamSelesai = $jamMulai->addMinutes($totalDurasi);
                @endphp
                <input type="text" class="form-control bg-light text-dark" value="{{ $jamSelesai->format('H:i') }}" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Diskon (Rp) :</label>
            <div class="col-md-10">
                <input type="text" class="form-control bg-light text-dark" 
                       value="Rp{{ number_format($order->diskon, 0, ',', '.') }}" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Total Harga :</label>
            <div class="col-md-10">
                <input type="text" class="form-control bg-light text-dark" 
                       value="Rp{{ number_format($order->layanans->sum('pivot.subtotal') - $order->diskon, 0, ',', '.') }}" readonly>
            </div>
        </div>
       
        <!-- Payment Method Section -->
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Metode Pembayaran :</label>
            
            <!-- Left Column (DP/Lunas) -->
            <div class="col-md-5">
                <select class="form-select bg-light text-dark" name="metode_pembayaran" disabled>
                    <option value="DP" {{ $order->metode_pembayaran === 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                    <option value="Lunas" {{ $order->metode_pembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            
            <!-- Right Column (Transfer/Cash) -->
            <div class="col-md-5">
                <select class="form-select bg-light text-dark" name="tipe_pembayaran" disabled>
                    <option value="Transfer" {{ $order->tipe_pembayaran === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="Cash" {{ $order->tipe_pembayaran === 'Cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Footer Buttons -->
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('orders.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-save">
            <i class="fas fa-save me-1"></i> Simpan Perubahan
        </button>
    </div>
</form>

<!-- Add Service Modal -->
<div class="modal fade" id="tambahOrderLayananModal" tabindex="-1" aria-labelledby="tambahOrderLayananModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h5 class="modal-title" id="modalTitle">Tambah Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editMode" value="0">
                <input type="hidden" id="currentLayananId">

                <div class="mb-3">
                    <label for="layanan_id" class="form-label">Pilih Layanan</label>
                    <select class="form-select" id="layanan_id" required>
                        <option value="" selected disabled>-- Pilih Layanan --</option>
                        @foreach($layanans as $layanan)
                            <option 
                                value="{{ $layanan->id_layanan }}"
                                data-kode="{{ $layanan->id_pricelist }}"
                                data-nama="{{ $layanan->nama_layanan }}"
                                data-harga="{{ $layanan->harga }}"
                                data-durasi="{{ $layanan->durasi }}">
                                {{ $layanan->nama_layanan }} | Rp{{ number_format($layanan->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="kode_layanan" class="form-label">Kode Layanan</label>
                    <input type="text" class="form-control" id="kode_layanan" readonly>
                </div>

                <div class="mb-3">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="number" class="form-control" id="qty" value="1" min="1">
                </div>

                <div class="mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" class="form-control" id="jam_mulai" value="{{ \Carbon\Carbon::parse($order->waktu_pembersihan)->format('H:i') }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="estimasi_selesai" class="form-label">Estimasi Selesai</label>
                    <input type="time" class="form-control" id="estimasi_selesai" readonly>
                </div>

                <div class="mb-3">
                    <label for="subtotal" class="form-label">Sub Total</label>
                    <input type="text" class="form-control" id="subtotal" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveLayananBtn">Tambahkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div class="modal fade" id="editPetugasModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Petugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editPetugasLayananId">
                <div class="mb-3">
                    <label for="petugasSelect" class="form-label">Pilih Petugas</label>
                    <select class="form-select" id="petugasSelect" required>
                        <option value="" selected disabled>-- Pilih Petugas --</option>
                        @foreach($petugas as $ptg)
                            <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">
                                {{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="petugasInput" class="form-label">Nama Petugas</label>
                    <input type="text" class="form-control" id="petugasInput" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="savePetugasBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        layananSelect: document.getElementById('layanan_id'),
        kodeLayananInput: document.getElementById('kode_layanan'),
        qtyInput: document.getElementById('qty'),
        jamMulaiInput: document.getElementById('jam_mulai'),
        estimasiInput: document.getElementById('estimasi_selesai'),
        subtotalInput: document.getElementById('subtotal'),
        saveBtn: document.getElementById('saveLayananBtn'),
        tableBody: document.querySelector('#layananOrderTable tbody'),
        hiddenInputsContainer: document.getElementById('hiddenInputsContainer'),
        modalTitle: document.getElementById('modalTitle'),
        editModeInput: document.getElementById('editMode'),
        currentLayananIdInput: document.getElementById('currentLayananId'),
        modal: new bootstrap.Modal(document.getElementById('tambahOrderLayananModal')),
        editPetugasModal: new bootstrap.Modal(document.getElementById('editPetugasModal')),
        petugasSelect: document.getElementById('petugasSelect'),
        petugasInput: document.getElementById('petugasInput'),
        editPetugasLayananId: document.getElementById('editPetugasLayananId'),
        savePetugasBtn: document.getElementById('savePetugasBtn')
    };

    // Fungsi untuk mengupdate estimasi dan subtotal
    function updateEstimasiDanSubtotal() {
        const selectedOption = elements.layananSelect.options[elements.layananSelect.selectedIndex];
        if (!selectedOption || selectedOption.disabled) {
            elements.kodeLayananInput.value = '';
            elements.estimasiInput.value = '';
            elements.subtotalInput.value = '';
            return;
        }

        const kode = selectedOption.getAttribute('data-kode');
        const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
        const durasi = parseInt(selectedOption.getAttribute('data-durasi')) || 0;
        const qty = parseInt(elements.qtyInput.value) || 1;

        elements.kodeLayananInput.value = kode;
        const subtotal = harga * qty;
        elements.subtotalInput.value = 'Rp' + subtotal.toLocaleString('id-ID');

        if (elements.jamMulaiInput.value && durasi > 0) {
            const [jam, menit] = elements.jamMulaiInput.value.split(':').map(Number);
            const totalMenit = jam * 60 + menit + (durasi * qty);
            const hasilJam = String(Math.floor(totalMenit / 60)).padStart(2, '0');
            const hasilMenit = String(totalMenit % 60).padStart(2, '0');
            elements.estimasiInput.value = `${hasilJam}:${hasilMenit}`;
        } else {
            elements.estimasiInput.value = '';
        }
    }

    // Event listeners untuk update estimasi dan subtotal
    elements.layananSelect.addEventListener('change', updateEstimasiDanSubtotal);
    elements.qtyInput.addEventListener('change', updateEstimasiDanSubtotal);

    // Handle petugas selection change
    elements.petugasSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            elements.petugasInput.value = selectedOption.getAttribute('data-nama');
        } else {
            elements.petugasInput.value = '';
        }
    });

    // Fungsi untuk mengupdate total durasi dan jam selesai
    function updateTotalDurasiDanJamSelesai() {
        const rows = elements.tableBody.querySelectorAll('tr');
        let totalDurasi = 0;
        
        // Hitung total durasi
        rows.forEach(row => {
            const estimasiText = row.cells[2].textContent;
            if (estimasiText) {
                const [jam, menit] = estimasiText.split(':').map(Number);
                const jamMulai = elements.jamMulaiInput.value;
                const [jamMulaiH, jamMulaiM] = jamMulai.split(':').map(Number);
                
                // Hitung durasi dalam menit
                const totalMenitMulai = jamMulaiH * 60 + jamMulaiM;
                const totalMenitSelesai = jam * 60 + menit;
                const durasi = totalMenitSelesai - totalMenitMulai;
                
                totalDurasi += durasi > 0 ? durasi : 0;
            }
        });
        
        // Update estimasi menit
        document.querySelector('input[name="estimasi_menit"]').value = totalDurasi;
        
        // Update jam selesai
        if (elements.jamMulaiInput.value && totalDurasi > 0) {
            const [jam, menit] = elements.jamMulaiInput.value.split(':').map(Number);
            const totalMenit = jam * 60 + menit + totalDurasi;
            const hasilJam = String(Math.floor(totalMenit / 60)).padStart(2, '0');
            const hasilMenit = String(totalMenit % 60).padStart(2, '0');
            document.querySelector('input[name="jam_selesai"]').value = `${hasilJam}:${hasilMenit}`;
        }
    }

    // Fungsi untuk mengupdate total harga
    function updateTotalHarga() {
        const rows = elements.tableBody.querySelectorAll('tr');
        let totalHarga = 0;
        
        rows.forEach(row => {
            const subtotalText = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '');
            totalHarga += parseInt(subtotalText) || 0;
        });
        
        // Kurangi diskon jika ada
        const diskonText = document.querySelector('input[name="diskon"]').value.replace(/\D/g, '');
        const diskon = parseInt(diskonText) || 0;
        const totalSetelahDiskon = Math.max(0, totalHarga - diskon);
        
        // Update total harga
        document.querySelector('input[name="total_harga"]').value = 'Rp' + totalSetelahDiskon.toLocaleString('id-ID');
    }

    // Handle save service button
    elements.saveBtn.addEventListener('click', function() {
        const selectedOption = elements.layananSelect.options[elements.layananSelect.selectedIndex];
        const isEditMode = elements.editModeInput.value === '1';

        if (!selectedOption || selectedOption.disabled) {
            alert("Silakan pilih layanan yang valid.");
            return;
        }

        const layananId = selectedOption.value;
        const kodeLayanan = elements.kodeLayananInput.value;
        const namaLayanan = selectedOption.getAttribute('data-nama');
        const estimasiSelesai = elements.estimasiInput.value;
        const subtotal = parseInt(elements.subtotalInput.value.replace(/\D/g, '')) || 0;
        const qty = parseInt(elements.qtyInput.value) || 1;

        if (!estimasiSelesai || subtotal <= 0) {
            alert("Data tidak valid. Silakan periksa kembali.");
            return;
        }

        const newRow = document.createElement('tr');
        newRow.dataset.layananId = layananId;
        newRow.innerHTML = `
            <td>${kodeLayanan}</td>
            <td>${namaLayanan}</td>
            <td>${estimasiSelesai}</td>
            <td>-</td>
            <td>Rp${subtotal.toLocaleString('id-ID')}</td>
            <td>
                <button type="button" class="btn btn-sm btn-info btn-edit-petugas" 
                    data-layanan-id="${layananId}"
                    data-current-petugas=""
                    data-current-nama-petugas="">
                    Edit Petugas
                </button>
                <button type="button" class="btn btn-sm btn-danger btn-delete">Hapus</button>
            </td>
        `;
        
        if (isEditMode) {
            const existingRow = elements.tableBody.querySelector(`tr[data-layanan-id="${elements.currentLayananIdInput.value}"]`);
            if (existingRow) {
                existingRow.replaceWith(newRow);
            }
        } else {
            const existingRow = elements.tableBody.querySelector(`tr[data-layanan-id="${layananId}"]`);
            if (existingRow) {
                alert("Layanan ini sudah ditambahkan.");
                return;
            }
            elements.tableBody.appendChild(newRow);
        }

        // Update hidden inputs
        const inputWrapper = document.createElement('div');
        inputWrapper.classList.add('hidden-input-wrapper');
        inputWrapper.dataset.layananId = layananId;
        inputWrapper.innerHTML = `
            <input type="hidden" name="layanans[]" value="${layananId}">
            <input type="hidden" name="subtotals[]" value="${subtotal}">
            <input type="hidden" name="estimasi_selesais[]" value="${estimasiSelesai}">
            <input type="hidden" name="petugas[]" value="">
            <input type="hidden" name="nama_petugas[]" value="">
        `;
        
        if (isEditMode) {
            const existingWrapper = elements.hiddenInputsContainer.querySelector(`.hidden-input-wrapper[data-layanan-id="${layananId}"]`);
            if (existingWrapper) {
                existingWrapper.replaceWith(inputWrapper);
            }
        } else {
            elements.hiddenInputsContainer.appendChild(inputWrapper);
        }

        // Update summary
        updateTotalDurasiDanJamSelesai();
        updateTotalHarga();

        // Reset modal
        elements.editModeInput.value = '0';
        elements.currentLayananIdInput.value = '';
        elements.layananSelect.selectedIndex = 0;
        elements.layananSelect.disabled = false;
        elements.qtyInput.value = 1;
        elements.kodeLayananInput.value = '';
        elements.estimasiInput.value = '';
        elements.subtotalInput.value = '';
        elements.modal.hide();
    });

    // Handle delete buttons
    elements.tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete')) {
            if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                const row = e.target.closest('tr');
                const layananId = row.dataset.layananId;
                row.remove();

                const hiddenWrapper = elements.hiddenInputsContainer.querySelector(`.hidden-input-wrapper[data-layanan-id="${layananId}"]`);
                if (hiddenWrapper) {
                    hiddenWrapper.remove();
                }

                // Update summary
                updateTotalDurasiDanJamSelesai();
                updateTotalHarga();
            }
        }
    });

    // Handle Edit Petugas button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-edit-petugas')) {
            const layananId = e.target.dataset.layananId;
            const currentPetugas = e.target.dataset.currentPetugas || '';
            const currentNamaPetugas = e.target.dataset.currentNamaPetugas || '';
            
            elements.editPetugasLayananId.value = layananId;
            
            // Reset select dan coba cocokkan dengan petugas saat ini jika ada
            elements.petugasSelect.selectedIndex = 0;
            elements.petugasInput.value = currentNamaPetugas;
            
            if (currentPetugas) {
                for (let i = 0; i < elements.petugasSelect.options.length; i++) {
                    if (elements.petugasSelect.options[i].value === currentPetugas) {
                        elements.petugasSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            
            elements.editPetugasModal.show();
        }
    });

    // Handle save petugas button
    elements.savePetugasBtn.addEventListener('click', function() {
        const layananId = elements.editPetugasLayananId.value;
        const selectedOption = elements.petugasSelect.options[elements.petugasSelect.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) {
            alert("Silakan pilih petugas yang valid.");
            return;
        }
        
        const petugasId = selectedOption.value;
        const namaPetugas = selectedOption.getAttribute('data-nama');
        const displayText = `${petugasId} - ${namaPetugas}`;
        
        // Update table display
        const row = document.querySelector(`tr[data-layanan-id="${layananId}"]`);
        if (row) {
            row.cells[3].textContent = displayText;
            
            // Update button data attributes
            const editBtn = row.querySelector('.btn-edit-petugas');
            if (editBtn) {
                editBtn.dataset.currentPetugas = petugasId;
                editBtn.dataset.currentNamaPetugas = namaPetugas;
            }
        }
        
        // Update hidden input
        const wrapper = elements.hiddenInputsContainer.querySelector(`.hidden-input-wrapper[data-layanan-id="${layananId}"]`);
        if (wrapper) {
            let petugasInput = wrapper.querySelector('input[name="petugas[]"]');
            let namaPetugasInput = wrapper.querySelector('input[name="nama_petugas[]"]');
            
            if (!petugasInput) {
                petugasInput = document.createElement('input');
                petugasInput.type = 'hidden';
                petugasInput.name = 'petugas[]';
                wrapper.appendChild(petugasInput);
            }
            
            if (!namaPetugasInput) {
                namaPetugasInput = document.createElement('input');
                namaPetugasInput.type = 'hidden';
                namaPetugasInput.name = 'nama_petugas[]';
                wrapper.appendChild(namaPetugasInput);
            }
            
            petugasInput.value = petugasId;
            namaPetugasInput.value = namaPetugas;
        }
        
        elements.editPetugasModal.hide();
    });
});
</script>

@endsection
