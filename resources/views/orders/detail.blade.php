@extends('layouts.app')

@section('title-content')
<h1>Detail Order</h1>
@endsection

@section('content')

<form method="POST" action="{{ route('orders.updateLayanan', $order->id_order) }}">
    @csrf
    @method('PUT')

    <h2 class="text-gray-800 mb-6 pb-3">Informasi Pesanan</h2>
    
    <div class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            <label class="md:w-1/4 font-semibold text-gray-700 text-sm">ID Order</label>
            <div class="md:w-3/4">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->id_order }}" readonly>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            <label class="md:w-1/4 font-semibold text-gray-700 text-sm">Nama Pelanggan</label>
            <div class="md:w-3/4">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->pelanggan->nama_pelanggan }}" readonly>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            <label class="md:w-1/4 font-semibold text-gray-700 text-sm">Lokasi Pengerjaan</label>
            <div class="md:w-3/4">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->alamat_lokasi ?? '-' }}" readonly>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            <label class="md:w-1/4 font-semibold text-gray-700 text-sm">Tanggal Pengerjaan</label>
            <div class="md:w-3/4">
                <input type="date" name="tanggal_pengerjaan" value="{{ $order->tanggal_pengerjaan }}" required
                        class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[#2ac6ea]/20 focus:border-[#2ac6ea] outline-none transition-all shadow-sm text-gray-700">
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
            <label class="md:w-1/4 font-semibold text-gray-700 text-sm">Waktu Pengerjaan</label>
            <div class="md:w-3/4">
                <input type="time" name="jam_pengerjaan" value="{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}" required
                        class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[#2ac6ea]/20 focus:border-[#2ac6ea] outline-none transition-all shadow-sm text-gray-700">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h4 class="text-lg font-bold text-gray-800 m-0">Daftar Layanan</h4>
            <button type="button" data-bs-toggle="modal" data-bs-target="#tambahOrderLayananModal" 
                    class="bg-[#2ac6ea] hover:bg-[#27b9d9] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 border-0">
                <i class="bi bi-plus-lg"></i> Tambah Layanan
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="layananOrderTable">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Layanan</th>
                        <th class="px-6 py-4 font-semibold">Durasi (Mnt)</th>
                        <th class="px-6 py-4 font-semibold">Petugas</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 font-semibold text-center">Action</th>
                    </tr>  
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach($order->orderDetails as $i => $detail)
                    @php
                        $namaPetugas = $detail->petugas->pluck('nama_petugas')->implode(', ');
                        $idPetugas = $detail->petugas->pluck('id_petugas')->implode(',');
                    @endphp
                    <tr data-layanan-id="{{ $detail->id_layanan_subkategori }}" data-id-order-detail="{{ $detail->id_order_detail }}" data-petugas-id="{{ $idPetugas }}" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                            <input type="hidden" name="id_order_detail[]" value="{{ $detail->id_order_detail }}">
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ ($detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-') . ' - ' . ($detail->layananSubkategori->nama_subkategori ?? '-') }}
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" name="durasi_layanan[]" min="5" step="5" value="{{ $detail->durasi_layanan ?? 60 }}" 
                                   class="durasi-input w-20 border border-gray-300 px-3 py-1.5 rounded-lg text-sm focus:ring-2 focus:ring-[#2ac6ea]/20 focus:border-[#2ac6ea] outline-none transition-all text-center">
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md {{ $detail->petugas->count() ? 'bg-blue-50 text-blue-700' : 'text-gray-400' }}">
                                <i class="bi bi-person-fill"></i>
                                {{ $detail->petugas->count() ? $detail->petugas->pluck('nama_petugas')->implode(', ') : 'Belum Atur' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-[#2ac6ea]">
                            Rp {{ number_format($detail->subtotal ?? $detail->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="btn-edit-petugas bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors border-0" 
                                    data-layanan-id="{{ $detail->id_layanan_subkategori }}"
                                    data-current-petugas="{{ $idPetugas }}"
                                    data-current-nama-petugas="{{ $namaPetugas }}"
                                    title="Edit Petugas">
                                    <i class="bi bi-person-lines-fill pointer-events-none"></i>
                                </button>
                                <button type="button" class="btn-delete bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors border-0" title="Hapus Layanan">
                                    <i class="bi bi-trash-fill pointer-events-none"></i>
                                </button>
                            </div>
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
            <label class="col-md-2 col-form-label fw-semibold">Total Durasi :</label>
            <div class="col-md-10">
                <input type="text" id="estimasi-durasi" class="form-control bg-light" value="0" readonly>
            </div>
        </div>
        
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold">Jam Selesai :</label>
            <div class="col-md-10">
                 <input type="text" id="jam-selesai" class="form-control bg-light" value="" readonly>
            </div>
        </div>
        
        @php
            // Untuk inisialisasi JS, agar konsisten
            $totalAsli = $order->orderDetails->sum(fn($detail) => $detail->subtotal ?? $detail->harga);
            $totalHargaInput = old('total_harga', $order->total_harga ?? $totalAsli);
            $diskonInput = old('diskon', $order->diskon ?? 0);
        @endphp

        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold">Diskon :</label>
            <div class="col-md-10">
                <input
                    type="number"
                    name="diskon"
                    min="0"
                    id="diskon_input"
                    class="form-control bg-light"
                    value="{{ $diskonInput }}"
                >
                <div class="mt-1 text-muted" id="diskonRupiah">
                    Rp {{ number_format($diskonInput, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold">Total Harga :</label>
            <div class="col-md-10">
                <input
                    type="number"
                    name="total_harga"
                    min="0"
                    id="total_harga_input"
                    class="form-control bg-light"
                    value="{{ $totalHargaInput }}"
                >
                <div class="mt-1 text-muted" id="totalHargaRupiah">
                    Rp {{ number_format($totalHargaInput, 0, ',', '.') }}
                </div>
            </div>
        </div>
       
        <!-- Metode Pembayaran -->
        <div class="row align-items-center mb-3">
            <label class="col-md-2 col-form-label fw-semibold">Status Pembayaran :</label>
            
            <!-- Left Column (DP/Lunas) -->
            <div class="col-md-5">
                <select class="form-select bg-light" name="metode_pembayaran">
                    <option value="DP" {{ $order->metode_pembayaran === 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                    <option value="Lunas" {{ $order->metode_pembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            
            <!-- Right Column (Transfer/Cash) -->
            <div class="col-md-5">
                <select class="form-select bg-light" name="tipe_pembayaran">
                    <option value="Transfer" {{ $order->tipe_pembayaran === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="Cash" {{ $order->tipe_pembayaran === 'Cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Footer Buttons -->
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('orders.index') }}" class="btn btn-back">
            Kembali
        </a>
        <button type="submit" class="btn btn-save">
            Simpan Perubahan
        </button>
    </div>
</form>

<!-- Modal Tambah Layanan 1-->
{{-- <div class="modal fade" id="tambahOrderLayananModal" tabindex="-1" aria-labelledby="tambahOrderLayananModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h5 class="modal-title" id="modalTitle">Tambah Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <div class="modal-body">
                <input type="hidden" id="editMode" value="0">
                <input type="hidden" id="currentLayananId">

                <div class="mb-3">
                    <label for="layanan_id" class="form-label">Pilih Layanan</label>
                    <select class="form-select" id="layanan_id" required>
                        <option value="" selected disabled>Pilih Layanan</option>
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
                </div>
            </div> --}}
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-save" id="saveLayananBtn">Tambahkan</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editMode" value="0">
                <input type="hidden" id="currentLayananId">

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchLayanan" class="form-control" placeholder="Cari nama layanan...">
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-3" id="layananCardContainer" style="max-height: 60vh; overflow-y: auto; overflow-x: hidden;">
                    @foreach($layanans as $layanan)
                    <div class="col layanan-item">
                        <div class="card h-100 layanan-card" 
                            data-id="{{ $layanan->id }}"
                            data-nama="{{ ($layanan->rootKategori->nama_rootkategori ?? '-') . ' - ' . $layanan->nama_subkategori }}"
                            data-harga="{{ $layanan->harga }}"
                            data-durasi="{{ $layanan->durasi ?? 0 }}">
                            <div class="card-body p-3">
                                <h6 class="card-subtitle mb-1 text-muted" style="font-size: 0.8rem;">
                                    {{ $layanan->rootKategori->nama_rootkategori ?? '-' }}
                                </h6>
                                <h5 class="card-title fs-6 fw-bold mb-2">
                                    {{ $layanan->nama_subkategori }}
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-primary rounded-pill">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $layanan->durasi ?? 60 }} Min</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal Tambah Layanan 2-->
<div class="modal fade" id="tambahOrderLayananModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        
        <div class="modal-content">
            
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-h-[85vh] overflow-hidden flex flex-col text-left">
                
                <input type="hidden" id="editMode" value="0">
                <input type="hidden" id="currentLayananId">

                <div class="p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Pilih Layanan Klinplus</h3>
                        <button type="button" data-bs-dismiss="modal" class="group relative text-gray-500 hover:text-gray-500 transition-colors p-1 rounded-md hover:bg-gray-200 border-0 bg-transparent">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative group mt-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchLayanan" placeholder="Cari layanan apa hari ini?" 
                            class="w-full border-gray-300 border pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
                    </div>
                </div>

                <div class="p-4 sm:p-6 overflow-y-auto flex-1 bg-gray-50/50" id="layananCardContainer">
                    @php
                        $groupedLayanans = $layanans->groupBy(function($item) {
                            return $item->rootKategori->nama_rootkategori ?? 'Lainnya';
                        });
                    @endphp

                    @foreach($groupedLayanans as $rootName => $subs)
                        <div class="mb-6 last:mb-0 layanan-group">
                            <p class="font-bold text-base text-cyan uppercase tracking-widest mb-2">{{ $rootName }}</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($subs as $sub)
                                    <div class="layanan-card cursor-pointer p-4 bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-cyan transition-all rounded-xl flex flex-col items-start min-h-[80px]"
                                         data-id="{{ $sub->id }}"
                                         data-nama="{{ $rootName . ' - ' . $sub->nama_subkategori }}"
                                         data-harga="{{ $sub->harga }}"
                                         data-durasi="{{ $sub->durasi ?? 0 }}">
                                        
                                        <span class="bodytext-dark mb-auto">{{ $sub->nama_subkategori }}</span>
                                        
                                        <span class="pricetext-sm mt-7">Rp {{ number_format($sub->harga, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
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
                    <label for="petugasSelect1" class="form-label">Pilih Petugas 1</label>
                    <select class="form-select" id="petugasSelect1" required>
                        <option value="" selected disabled>Pilih Petugas 1</option>
                        @foreach($petugas as $ptg)
                            <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">
                                {{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="petugasSelect2" class="form-label">Pilih Petugas 2 (Opsional)</label>
                    <select class="form-select" id="petugasSelect2">
                        <option value="" selected>Pilih Petugas 2<option>
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
                <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-save" id="savePetugasBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- KONFIGURASI TOASTR ---
    toastr.options = {
        "closeButton": true,
        "progressBar": false,
        "positionClass": "toast-top-right", // Muncul di kanan atas
        "timeOut": "4000", // Hilang otomatis setelah 4 detik
        "extendedTimeOut": "1000",
    };

    // --- TRIGGER TOASTR BERDASARKAN SESSION LARAVEL ---
    @if(session('success'))
        toastr.success("{{ session('success') }}", "Berhasil!");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}", "Gagal!");
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error("{{ $error }}", "Validasi Gagal!");
        @endforeach
    @endif

    const elements = {
        jamMulaiInput: document.getElementById('jam_mulai'),
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

    addDurasiInputListeners();
    updateTotalDurasiDanJamSelesai();
    syncHiddenInputsWithTable();

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

    function syncHiddenInputsWithTable() {
        elements.hiddenInputsContainer.innerHTML = ''; // Bersihkan dulu

        document.querySelectorAll('#layananOrderTable tbody tr').forEach((row, index) => {
            const idOrderDetailInput = row.querySelector('input[name="id_order_detail[]"]');
            const idOrderDetail = idOrderDetailInput ? idOrderDetailInput.value : '';
            const layananId     = row.dataset.layananId;
            const durasi        = row.querySelector('.durasi-input')?.value || '60';
            const petugasId     = row.dataset.petugasId || '';
            const harga         = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '').trim() || '0';
            const petugasArr    = petugasId.split(',').filter(Boolean);

            // Masukkan hidden input id_order_detail, layanan, durasi, subtotal
            elements.hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="id_order_detail[]"  value="${idOrderDetail}">
                <input type="hidden" name="layanans[]"         value="${layananId}">
                <input type="hidden" name="durasi_layanan[]"   value="${durasi}">
                <input type="hidden" name="subtotals[]"        value="${harga}">
            `);

            // Masukkan petugas sebagai array dua dimensi: petugas[0][], petugas[1][]
            petugasArr.forEach(pid => {
                elements.hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="petugas[${index}][]" value="${pid}">
                `);
            });
        });
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

    // --- FITUR BARU: Search Grouping & One-Click Add ---
    
    // 1. Fitur Search Layanan (Berdasarkan Kategori)
    const searchInput = document.getElementById('searchLayanan');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            document.querySelectorAll('.layanan-group').forEach(group => {
                let hasVisibleCard = false;
                
                group.querySelectorAll('.layanan-card').forEach(card => {
                    const namaLayanan = card.dataset.nama.toLowerCase();
                    if (namaLayanan.includes(searchTerm)) {
                        card.style.display = '';
                        hasVisibleCard = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Sembunyikan judul kategori jika semua isinya tidak cocok
                group.style.display = hasVisibleCard ? '' : 'none';
            });
        });
    }

    // 2. Klik Card Langsung Tambah Layanan (Lebih Praktis & Modern)
    document.querySelectorAll('.layanan-card').forEach(card => {
        card.addEventListener('click', function() {
            const isEditMode = elements.editModeInput.value === '1';
            const layananId = this.dataset.id;
            const namaLayanan = this.dataset.nama;
            const harga = this.dataset.harga;

            // Cek duplikasi
            const existingRow = elements.tableBody.querySelector(`tr[data-layanan-id="${layananId}"]`);
            if (existingRow && !isEditMode) {
                toastr.error("Layanan ini sudah ada di daftar order.", "Peringatan!");
                return;
            }

            // Tambah Row ke Tabel
            const newRow = document.createElement('tr');
            newRow.dataset.layananId = layananId;
            newRow.innerHTML = `
                <td></td>
                <td>${namaLayanan}</td>
                <td><input type="number" class="form-control durasi-input" name="durasi_layanan[]" min="5" step="5" value="60" style="width:80px;"></td>
                <td>-</td>
                <td>Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info btn-edit-petugas" 
                        data-layanan-id="${layananId}"
                        data-current-petugas=""
                        data-current-nama-petugas="">
                        Edit Petugas
                    </button>
                    <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash-fill"></i></button>
                </td>
            `;
            
            if (isEditMode && existingRow) {
                existingRow.replaceWith(newRow);
            } else {
                elements.tableBody.appendChild(newRow);
            }
            
            // Update Event Listeners & Nomor
            updateTableNumbering();
            addDurasiInputListeners();

            // Tambah Hidden Input untuk Controller
            const inputWrapper = document.createElement('div');
            inputWrapper.classList.add('hidden-input-wrapper');
            inputWrapper.dataset.layananId = layananId;
            inputWrapper.innerHTML = `
                <input type="hidden" name="id_order_detail[]" value="">
                <input type="hidden" name="layanans[]" value="${layananId}">
                <input type="hidden" name="subtotals[]" value="${harga}">
                <input type="hidden" name="durasi_layanan[]" value="60">
            `;
            
            if (isEditMode) {
                const existingWrapper = elements.hiddenInputsContainer.querySelector(`.hidden-input-wrapper[data-layanan-id="${layananId}"]`);
                if (existingWrapper) {
                    existingWrapper.replaceWith(inputWrapper);
                }
            } else {
                elements.hiddenInputsContainer.appendChild(inputWrapper);
            }

            // Update Total Kalkulasi
            updateTotalDurasiDanJamSelesai();
            updateTotalHarga();

            // Reset Search Form
            if(searchInput) searchInput.value = '';
            document.querySelectorAll('.layanan-group, .layanan-card').forEach(el => el.style.display = '');

            // Tutup Modal & Sinkronisasi Ulang
            syncHiddenInputsWithTable();
            elements.modal.hide();
            toastr.success("Layanan berhasil ditambahkan ke tabel", "Berhasil!");
        });
    });

    // Handle Pilihan Petugas
    document.getElementById('petugasSelect1').addEventListener('change', updateNamaPetugas);
    document.getElementById('petugasSelect2').addEventListener('change', updateNamaPetugas);

    function updateNamaPetugas() {
        const select1 = document.getElementById('petugasSelect1');
        const select2 = document.getElementById('petugasSelect2');
        let nama = '';
        if (select1.value) {
            nama += select1.options[select1.selectedIndex].getAttribute('data-nama');
        }
        if (select2.value) {
            nama += ', ' + select2.options[select2.selectedIndex].getAttribute('data-nama');
        }
        document.getElementById('petugasInput').value = nama;
    }

    // Handle Tombol Edit Petugas
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-edit-petugas')) {
            const layananId = e.target.dataset.layananId;
            const currentPetugas = e.target.dataset.currentPetugas || '';
            const currentNamaPetugas = e.target.dataset.currentNamaPetugas || '';
            document.getElementById('editPetugasLayananId').value = layananId;

            // Reset select
            document.getElementById('petugasSelect1').selectedIndex = 0;
            document.getElementById('petugasSelect2').selectedIndex = 0;
            document.getElementById('petugasInput').value = currentNamaPetugas;

            // Jika sudah ada data petugas, isi select
            if (currentPetugas) {
                const petugasArr = currentPetugas.split(',');
                if (petugasArr[0]) {
                    document.getElementById('petugasSelect1').value = petugasArr[0].trim();
                }
                if (petugasArr[1]) {
                    document.getElementById('petugasSelect2').value = petugasArr[1].trim();
                }
            }
            updateNamaPetugas();
            elements.editPetugasModal.show();
            syncHiddenInputsWithTable();
        }
    });

    // Handle Tombol Simpan Petugas
    document.getElementById('savePetugasBtn').addEventListener('click', function() {
        const layananId = document.getElementById('editPetugasLayananId').value;
        const select1 = document.getElementById('petugasSelect1');
        const select2 = document.getElementById('petugasSelect2');
        const petugasId1 = select1.value;
        const petugasId2 = select2.value;
        const namaPetugas1 = petugasId1 ? select1.options[select1.selectedIndex].getAttribute('data-nama') : '';
        const namaPetugas2 = petugasId2 ? select2.options[select2.selectedIndex].getAttribute('data-nama') : '';
        let displayText = namaPetugas1;
        if (namaPetugas2) {
            displayText += ', ' + namaPetugas2;
        }

        // Update table display
        const row = document.querySelector(`tr[data-layanan-id="${layananId}"]`);
        if (row) {
            row.cells[3].textContent = displayText;
            row.dataset.petugasId = [petugasId1, petugasId2].filter(Boolean).join(',');
            row.dataset.namaPetugas = displayText;
            // Update button data attributes
            const editBtn = row.querySelector('.btn-edit-petugas');
            if (editBtn) {
                editBtn.dataset.currentPetugas = [petugasId1, petugasId2].filter(Boolean).join(',');
                editBtn.dataset.currentNamaPetugas = displayText;
            }
        }
                
        syncHiddenInputsWithTable();
        elements.editPetugasModal.hide();
    });

    // Format Rupiah untuk Total Harga
    (function() {
        // Ambil nilai awal dari Blade
        var totalAsli = {{ $totalAsli }};
        var totalHargaAwal = {{ $totalHargaInput }};
        var diskonAwal = {{ $diskonInput }};

        function formatRupiah(val) {
            let num = parseInt(val.replace(/\D/g, '')) || 0;
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        var diskonInput = document.getElementById('diskon_input');
        var diskonDisplay = document.getElementById('diskonRupiah');
        var totalInput = document.getElementById('total_harga_input');
        var totalDisplay = document.getElementById('totalHargaRupiah');

        // SET NILAI AWAL & FORMAT
        if (diskonInput && diskonDisplay) {
            diskonInput.value = diskonAwal;
            diskonDisplay.textContent = formatRupiah(diskonInput.value);
        }
        if (totalInput && totalDisplay) {
            totalInput.value = totalHargaAwal;
            totalDisplay.textContent = formatRupiah(totalInput.value);
        }

        // Listener: update otomatis total harga jika diskon diubah
        if (diskonInput && totalInput && totalDisplay && diskonDisplay) {
            diskonInput.addEventListener('input', function () {
                diskonDisplay.textContent = formatRupiah(diskonInput.value);
                var diskon = parseInt(diskonInput.value) || 0;
                var hargaSetelahDiskon = Math.max(0, totalAsli - diskon);
                totalInput.value = hargaSetelahDiskon;
                totalDisplay.textContent = formatRupiah(totalInput.value);
            });
        }

        // Listener: format tampilan total harga saat diubah manual
        if (totalInput && totalDisplay) {
            totalInput.addEventListener('input', function () {
                totalDisplay.textContent = formatRupiah(totalInput.value);
            });
        }
    })();
});
</script>
@endsection
