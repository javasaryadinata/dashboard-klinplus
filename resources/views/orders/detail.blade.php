@extends('layouts.app')

@section('title-content')
<h1>Detail Order</h1>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success" id="order-success-alert">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('orders.updateLayanan', $order->id_order) }}">
    @csrf
    @method('PUT')

    <div class="detail-table">

        <!-- Informasi Pelanggan -->
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
            <label class="col-md-2 col-form-label fw-semibold text-dark">Lokasi Pengerjaan :</label>
            <div class="col-md-10">
                <input type="text" class="form-control bg-light text-dark" value="{{ $order->alamat_lokasi ?? '-' }}" readonly>
            </div>
        </div>

        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Tanggal Pengerjaan :</label>
            <div class="col-md-10">
                <input type="date" class="form-control bg-light text-dark" name="tanggal_pengerjaan" value="{{ $order->tanggal_pengerjaan }}" required>
            </div>
        </div>

        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Waktu Pengerjaan :</label>
            <div class="col-md-10">
                <input type="time" class="form-control bg-light text-dark" name="jam_pengerjaan" value="{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}" required>
            </div>
        </div>
    </div>

    {{-- Button Tambah Layanan --}}
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
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Durasi (Menit)</th>
                        <th>Nama Petugas</th>
                        <th>Harga</th>
                        <th>Action</th>
                    </tr>  
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                    <tr data-layanan-id="{{ $detail->id_layanan_subkategori }}" data-petugas-id="{{ $detail->id_petugas ?? '' }}">
                        <td>
                            {{ $loop->iteration }}
                            <input type="hidden" name="id_order_detail[]" value="{{ $detail->id_order_detail }}">
                        </td>
                        <td>
                            {{ 
                                ($detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-') 
                                . ' - ' . 
                                ($detail->layananSubkategori->nama_subkategori ?? '-') 
                            }}
                        </td>
                        <td>
                            <input type="number" name="durasi_layanan[]" class="form-control durasi-input" min="5" step="5"
                            value="{{ $detail->durasi_layanan ?? 60 }}" style="width:80px;">
                        </td>
                        <td>
                            @if($detail->petugas)
                                {{ $detail->petugas->id_petugas }} - {{ $detail->petugas->nama_petugas }}
                            @else
                                -
                            @endif
                        </td>
                        <td>Rp{{ number_format($detail->subtotal ?? $detail->harga, 0, ',', '.') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info btn-edit-petugas" 
                                data-layanan-id="{{ $detail->id_layanan_subkategori }}"
                                data-current-petugas="{{ $detail->petugas ?? '' }}"
                                data-current-nama-petugas="{{ $detail->nama_petugas ?? '' }}">
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
        {{-- @foreach($order->orderDetails as $detail)
            <div class="hidden-input-wrapper" data-layanan-id="{{ $detail->id_layanan_subkategori }}">
                <input type="hidden" name="layanans[]" value="{{ $detail->id_layanan_subkategori }}">
                <input type="hidden" name="subtotals[]" value="{{ $detail->subtotal ?? $detail->harga }}">
                <input type="hidden" name="durasi_layanan[]" value="{{ $detail->durasi_layanan ?? 60 }}">
                <input type="hidden" name="petugas[]" value="{{ $detail->id_petugas ?? '' }}">
                <input type="hidden" name="nama_petugas[]" value="{{ $detail->nama_petugas ?? '' }}">
            </div>
        @endforeach --}}
    </div>

    <!-- Informasi Order -->
    <div class="detail-table">
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Total Durasi :</label>
            <div class="col-md-10">
                <input type="text" id="estimasi-durasi" class="form-control bg-light text-dark" value="0" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Jam Selesai :</label>
            <div class="col-md-10">
                 <input type="text" id="jam-selesai" class="form-control bg-light text-dark" value="" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Diskon :</label>
            <div class="col-md-10">
                <input type="text" class="form-control bg-light text-dark" 
                       value="Rp {{ number_format($order->diskon, 0, ',', '.') }}" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Total Harga :</label>
            <div class="col-md-10">
                @php
                    $totalHarga = $order->orderDetails->sum(function($detail) {
                        return $detail->subtotal ?? $detail->harga;
                    });
                @endphp
                <input type="text" class="form-control bg-light text-dark" 
                    value="Rp {{ number_format($totalHarga - $order->diskon, 0, ',', '.') }}" readonly>
            </div>
        </div>
       
        <!-- Metode Pembayaran -->
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold text-dark">Status Pembayaran :</label>
            
            <!-- Left Column (DP/Lunas) -->
            <div class="col-md-5">
                <select class="form-select bg-light text-dark" name="metode_pembayaran">
                    <option value="DP" {{ $order->metode_pembayaran === 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                    <option value="Lunas" {{ $order->metode_pembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            
            <!-- Right Column (Transfer/Cash) -->
            <div class="col-md-5">
                <select class="form-select bg-light text-dark" name="tipe_pembayaran">
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

<!-- Modal Tambah Layanan -->
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
                                value="{{ $layanan->id }}"
                                data-kode="{{ $layanan->id }}"
                                data-nama="{{ ($layanan->rootKategori->nama_rootkategori ?? '-') . ' - ' . $layanan->nama_subkategori }}"
                                data-harga="{{ $layanan->harga }}"
                                data-durasi="{{ $layanan->durasi ?? 0 }}">
                                {{ ($layanan->rootKategori->nama_rootkategori ?? '-') . ' - ' . $layanan->nama_subkategori }} | Rp{{ number_format($layanan->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" class="form-control" id="jam_mulai" value="{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}" readonly>
                </div> --}}

                {{-- <div class="mb-3">
                    <label for="estimasi_selesai" class="form-label">Estimasi Selesai</label>
                    <input type="time" class="form-control" id="estimasi_selesai" readonly>
                </div>

                <div class="mb-3">
                    <label for="subtotal" class="form-label">Sub Total</label>
                    <input type="text" class="form-control" id="subtotal" readonly>
                </div> --}}
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
        // kodeLayananInput: document.getElementById('kode_layanan'),
        // qtyInput: document.getElementById('qty'),
        jamMulaiInput: document.getElementById('jam_mulai'),
        // estimasiInput: document.getElementById('estimasi_selesai'),
        // subtotalInput: document.getElementById('subtotal'),
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

    // Panggil sekali saat halaman pertama kali load
    addDurasiInputListeners();
    updateTotalDurasiDanJamSelesai();
    syncHiddenInputsWithTable();

    // Fungsi untuk mengupdate estimasi dan subtotal
    // function updateEstimasiDanSubtotal() {
    //     const selectedOption = elements.layananSelect.options[elements.layananSelect.selectedIndex];
    //     if (!selectedOption || selectedOption.disabled) {
    //         elements.kodeLayananInput.value = '';
    //         elements.estimasiInput.value = '';
    //         elements.subtotalInput.value = '';
    //         return;
    //     }

    //     const kode = selectedOption.getAttribute('data-kode');
    //     const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
    //     const durasi = parseInt(selectedOption.getAttribute('data-durasi')) || 0;
    //     const qty = parseInt(elements.qtyInput.value) || 1;

    //     elements.kodeLayananInput.value = kode;
    //     const subtotal = harga * qty;
    //     elements.subtotalInput.value = 'Rp' + subtotal.toLocaleString('id-ID');

    //     if (elements.jamMulaiInput.value && durasi > 0) {
    //         const [jam, menit] = elements.jamMulaiInput.value.split(':').map(Number);
    //         const totalMenit = jam * 60 + menit + (durasi * qty);
    //         const hasilJam = String(Math.floor(totalMenit / 60)).padStart(2, '0');
    //         const hasilMenit = String(totalMenit % 60).padStart(2, '0');
    //         elements.estimasiInput.value = `${hasilJam}:${hasilMenit}`;
    //     } else {
    //         elements.estimasiInput.value = '';
    //     }
    // }

    // Event listeners untuk update estimasi dan subtotal
    // elements.layananSelect.addEventListener('change', updateEstimasiDanSubtotal);
    // elements.qtyInput.addEventListener('change', updateEstimasiDanSubtotal);

    // Handle Pilihan Petugas
    elements.petugasSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value) {
            elements.petugasInput.value = selectedOption.getAttribute('data-nama');
        } else {
            elements.petugasInput.value = '';
        }
    });

    function addDurasiInputListeners() {
        document.querySelectorAll('.durasi-input').forEach(input => {
            input.addEventListener('input', updateTotalDurasiDanJamSelesai);
            input.addEventListener('input', syncHiddenInputsWithTable);
        });
    }

    // Fungsi Update Total Durasi dan Jam Selesai
    function updateTotalDurasiDanJamSelesai() {
        let totalDurasi = 0;
        document.querySelectorAll('.durasi-input').forEach(input => {
            totalDurasi += parseInt(input.value) || 0;
        });

        // Update estimasi durasi
        document.getElementById('estimasi-durasi').value = totalDurasi + ' Menit';

        // Hitung jam selesai
        const jamMulai = "{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}";
        if (jamMulai) {
            const [jam, menit] = jamMulai.split(':').map(Number);
            const totalMenit = jam * 60 + menit + totalDurasi;
            const hasilJam = String(Math.floor(totalMenit / 60)).padStart(2, '0');
            const hasilMenit = String(totalMenit % 60).padStart(2, '0');
            document.getElementById('jam-selesai').value = `${hasilJam}:${hasilMenit} WIB`;
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        syncHiddenInputsWithTable();

        this.submit();
    });

    // Trigger update saat durasi diubah
    document.querySelectorAll('.durasi-input').forEach(input => {
        input.addEventListener('input', updateTotalDurasiDanJamSelesai);
    });

    document.querySelectorAll('.durasi-input').forEach(input => {
        input.addEventListener('input', syncHiddenInputsWithTable);
    });

    // Fungsi Update Total Harga
    function updateTotalHarga() {
        const rows = elements.tableBody.querySelectorAll('tr');
        let totalHarga = 0;
        
        rows.forEach(row => {
            const subtotalText = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '');
            totalHarga += parseInt(subtotalText) || 0;
        });
        
        // Kurangi diskon jika ada
        const diskon = {{ $order->diskon ?? 0 }};
        const totalSetelahDiskon = Math.max(0, totalHarga - diskon);
        
        // Update total harga
        const totalHargaInput = document.querySelector('input[name="total_harga"]');
        if (totalHargaInput) {
            totalHargaInput.value = 'Rp ' + totalSetelahDiskon.toLocaleString('id-ID');
        }
    }

    // Fungsi Update Penomoran Tabel
    function updateTableNumbering() {
        const rows = elements.tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            row.cells[0].textContent = idx + 1;
        });
    }

    // Handle Tombol Tambah Layanan
    elements.saveBtn.addEventListener('click', function() {
        const selectedOption = elements.layananSelect.options[elements.layananSelect.selectedIndex];
        const isEditMode = elements.editModeInput.value === '1';

        if (!selectedOption || selectedOption.disabled) {
            alert("Silakan pilih layanan yang valid.");
            return;
        }

        const layananId = selectedOption.value;
        // const kodeLayanan = elements.kodeLayananInput.value;
        const namaLayanan = selectedOption.getAttribute('data-nama');
        // const estimasiSelesai = elements.estimasiInput.value;
        // const subtotal = parseInt(elements.subtotalInput.value.replace(/\D/g, '')) || 0;
        // const qty = parseInt(elements.qtyInput.value) || 1;
        const harga = selectedOption.getAttribute('data-harga') || 0;

        // if (!estimasiSelesai || subtotal <= 0) {
        //     alert("Data tidak valid. Silakan periksa kembali.");
        //     return;
        // }

        // Cek duplikasi
        const existingRow = elements.tableBody.querySelector(`tr[data-layanan-id="${layananId}"]`);
        if (existingRow && !isEditMode) {
            alert("Layanan ini sudah ditambahkan.");
            return;
        }

        const newRow = document.createElement('tr');
        newRow.dataset.layananId = layananId;
        newRow.innerHTML = `
            <td></td>
            <td>${namaLayanan}</td>
            <td><input type="number" class="form-control durasi-input" name="durasi[]" min="1" value="60" style="width:80px;"></td>
            <td>-</td>
            <td>Rp${parseInt(harga).toLocaleString('id-ID')}</td>
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
        
        if (isEditMode && existingRow) {
            existingRow.replaceWith(newRow);
        } else {
            elements.tableBody.appendChild(newRow);
        }
        updateTableNumbering();
        addDurasiInputListeners();

        // Update hidden inputs
        const inputWrapper = document.createElement('div');
        inputWrapper.classList.add('hidden-input-wrapper');
        inputWrapper.dataset.layananId = layananId;
        // <input type="hidden" name="estimasi_selesais[]" value=""> posisi digunakan: setelah subtotals
        inputWrapper.innerHTML = `
            <input type="hidden" name="layanans[]" value="${layananId}">
            <input type="hidden" name="subtotals[]" value="${harga}">
            <input type="hidden" name="petugas[]" value="">
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
        // updateTotalDurasiDanJamSelesai();
        // updateTotalHarga();

        // Reset modal
        elements.editModeInput.value = '0';
        elements.currentLayananIdInput.value = '';
        elements.layananSelect.selectedIndex = 0;
        // elements.layananSelect.disabled = false;
        // elements.qtyInput.value = 1;
        // elements.kodeLayananInput.value = '';
        // elements.estimasiInput.value = '';
        // elements.subtotalInput.value = '';
        updateTableNumbering();
        syncHiddenInputsWithTable();
        elements.modal.hide();
    });

    // Handle Tombol Hapus Layanan
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
                updateTableNumbering();
                // Update summary
                // updateTotalDurasiDanJamSelesai();
                updateTotalHarga();
                updateTableNumbering();
                syncHiddenInputsWithTable();
            }
        }
    });

    function syncHiddenInputsWithTable() {
        elements.hiddenInputsContainer.innerHTML = '';
        const rows = elements.tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const layananId = row.dataset.layananId;
            const durasi = row.cells[2].querySelector('input') ? row.cells[2].querySelector('input').value : '';
            const petugasText = row.cells[3].textContent.trim();
            const petugasId = row.dataset.petugasId || '';
            const harga = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '').replace(/ /g, '') || 0;
            const wrapper = document.createElement('div');
            wrapper.classList.add('hidden-input-wrapper');
            wrapper.dataset.layananId = layananId;
            wrapper.innerHTML = `
                <input type="hidden" name="layanans[]" value="${layananId}">
                <input type="hidden" name="durasi_layanan[]" value="${durasi}">
                <input type="hidden" name="subtotals[]" value="${harga}">
                <input type="hidden" name="petugas[]" value="${petugasId}">
            `;
            elements.hiddenInputsContainer.appendChild(wrapper);
        });
    }

    // Handle Tombol Edit Petugas
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
            syncHiddenInputsWithTable();
        }
    });

    // Handle Tombol Simpan Petugas
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
            row.dataset.petugasId = petugasId;
            row.dataset.namaPetugas = namaPetugas;
            // Update button data attributes
            const editBtn = row.querySelector('.btn-edit-petugas');
            if (editBtn) {
                editBtn.dataset.currentPetugas = petugasId;
                editBtn.dataset.currentNamaPetugas = namaPetugas;
            }
        }
        
        // Update hidden input
        // const wrapper = elements.hiddenInputsContainer.querySelector(`.hidden-input-wrapper[data-layanan-id="${layananId}"]`);
        // if (wrapper) {
        //     let petugasInput = wrapper.querySelector('input[name="petugas[]"]');
        //     if (!petugasInput) {
        //         petugasInput = document.createElement('input');
        //         petugasInput.type = 'hidden';
        //         petugasInput.name = 'petugas[]';
        //         wrapper.appendChild(petugasInput);
        //     }
        //     petugasInput.value = petugasId;
        // }
        syncHiddenInputsWithTable();
        elements.editPetugasModal.hide();
    });
});
</script>
@endsection
