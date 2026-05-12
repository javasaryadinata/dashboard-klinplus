@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Detail Order</h3>
@endsection

@section('content')
<div x-data="{ isLayananModalOpen: false, isPetugasModalOpen: false }" 
     @open-layanan-modal.window="isLayananModalOpen = true"
     @close-layanan-modal.window="isLayananModalOpen = false"
     @open-petugas-modal.window="isPetugasModalOpen = true"
     @close-petugas-modal.window="isPetugasModalOpen = false">

<form method="POST" action="{{ route('orders.updateLayanan', $order->id_order) }}">
    @csrf
    @method('PUT')

    <h2 class="text-gray-800 mb-6 pb-3 font-bold">Informasi Pesanan</h2>

    <div class="space-y-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
            <label class="font-semibold text-gray-700 text-sm">ID Order</label>
            <div class="md:col-span-3">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->id_order }}" readonly>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
            <label class="font-semibold text-gray-700 text-sm">Nama Pelanggan</label>
            <div class="md:col-span-3">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->pelanggan->nama_pelanggan }}" readonly>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
            <label class="font-semibold text-gray-700 text-sm">Lokasi Pengerjaan</label>
            <div class="md:col-span-3">
                <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed" value="{{ $order->alamat_lokasi ?? '-' }}" readonly>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
            <label class="font-semibold text-gray-700 text-sm">Tanggal Pengerjaan</label>
            <div class="md:col-span-3">
                <input type="date" name="tanggal_pengerjaan" value="{{ $order->tanggal_pengerjaan }}" required
                        class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[#2ac6ea]/20 focus:border-[#2ac6ea] outline-none transition-all shadow-sm text-gray-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-2">
            <label class="font-semibold text-gray-700 text-sm">Waktu Pengerjaan</label>
            <div class="md:col-span-3">
                <input type="time" name="jam_pengerjaan" value="{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}" required
                        class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-[#2ac6ea]/20 focus:border-[#2ac6ea] outline-none transition-all shadow-sm text-gray-700">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h4 class="text-lg font-bold text-gray-800 m-0">Daftar Layanan</h4>
            <button type="button" @click="$dispatch('open-layanan-modal')"
                    class="bg-cyan hover:bg-[#27b9d9] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 border-0">
                Tambah Layanan
            </button>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[800px]" id="layananOrderTable">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No</th>
                        <th class="px-6 py-4 font-semibold">Nama Layanan</th>
                        <th class="px-6 py-4 font-semibold">Durasi (Menit)</th>
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
                                    class="durasi-input w-20 border border-gray-300 px-3 py-1.5 rounded-lg text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all text-center">
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md {{ $detail->petugas->count() ? 'bg-blue-50 text-blue-700' : 'text-gray-400 bg-gray-50' }}">
                                {{ $detail->petugas->count() ? $detail->petugas->pluck('nama_petugas')->implode(', ') : 'Belum Atur' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-cyan">
                            Rp {{ number_format($detail->subtotal ?? $detail->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" class="btn-edit-petugas bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors border-0"
                                    data-layanan-id="{{ $detail->id_layanan_subkategori }}"
                                    data-current-petugas="{{ $idPetugas }}"
                                    data-current-nama-petugas="{{ $namaPetugas }}"
                                    title="Edit Petugas">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button type="button" class="btn-delete bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors border-0" title="Hapus Layanan">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="hiddenInputsContainer"></div>

    {{-- Informasi Kalkulasi --}}
    <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 mb-4">
            <label class="font-semibold text-gray-700">Total Durasi :</label>
            <div class="md:col-span-3">
                <input type="text" id="estimasi-durasi" class="w-full bg-gray-100 border border-gray-200 text-gray-500 rounded-xl px-4 py-2" value="0" readonly>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 mb-4">
            <label class="font-semibold text-gray-700">Jam Selesai :</label>
            <div class="md:col-span-3">
                <input type="text" id="jam-selesai" class="w-full bg-gray-100 border border-gray-200 text-gray-500 rounded-xl px-4 py-2" value="" readonly>
            </div>
        </div>

        @php
            $totalAsli = $order->orderDetails->sum(fn($detail) => $detail->subtotal ?? $detail->harga);
            $totalHargaInput = old('total_harga', $order->total_harga ?? $totalAsli);
            $diskonInput = old('diskon', $order->diskon ?? 0);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 items-start gap-4 mb-4">
            <label class="font-semibold text-gray-700 mt-2">Diskon :</label>
            <div class="md:col-span-3">
                <input type="number" name="diskon" min="0" id="diskon_input" class="w-full border border-gray-300 px-4 py-2 rounded-xl focus:border-cyan focus:ring-2 focus:ring-cyan/20 outline-none" value="{{ $diskonInput }}">
                <div class="mt-1 text-sm text-gray-500" id="diskonRupiah">Rp {{ number_format($diskonInput, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-start gap-4 mb-4">
            <label class="font-semibold text-gray-700 mt-2">Total Harga :</label>
            <div class="md:col-span-3">
                <input type="number" name="total_harga" min="0" id="total_harga_input" class="w-full bg-gray-100 border border-gray-200 text-gray-500 px-4 py-2 rounded-xl outline-none cursor-not-allowed" value="{{ $totalHargaInput }}" readonly>
                <div class="mt-1 text-sm text-grey" id="totalHargaRupiah">Rp {{ number_format($totalHargaInput, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 mb-4">
            <label class="font-semibold text-gray-700">Status Pembayaran :</label>
            <div class="md:col-span-3 flex gap-4">
                <select class="w-1/2 border border-gray-300 px-4 py-2 rounded-xl outline-none focus:border-cyan" name="metode_pembayaran">
                    <option value="DP" {{ $order->metode_pembayaran === 'DP' ? 'selected' : '' }}>DP (Down Payment)</option>
                    <option value="Lunas" {{ $order->metode_pembayaran === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
                <select class="w-1/2 border border-gray-300 px-4 py-2 rounded-xl outline-none focus:border-cyan" name="tipe_pembayaran">
                    <option value="Transfer" {{ $order->tipe_pembayaran === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="Cash" {{ $order->tipe_pembayaran === 'Cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Footer Buttons -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{{ request('ref') === 'pembayaran' ? route('pembayaran.index') : route('orders.index') }}}"
            class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition-colors">
            Kembali
        </a>
        <button type="submit" class="px-5 py-2.5 bg-cyan hover:bg-[#27b9d9] text-white font-medium rounded-xl transition-colors shadow-sm">
            Simpan Perubahan
        </button>
    </div>
</form>
    
<!-- Modal Tambah Layanan -->
<div x-show="isLayananModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="isLayananModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="isLayananModalOpen" @click.away="isLayananModalOpen = false" x-transition class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-4xl rounded-2xl flex flex-col max-h-[85vh] overflow-hidden">

            <input type="hidden" id="editMode" value="0">
            <input type="hidden" id="currentLayananId">

            <div class="p-4 border-b border-gray-100 bg-white sticky top-0 z-10 shrink-0">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Pilih Layanan Klinplus</h3>
                    <button @click="isLayananModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" id="searchLayanan" placeholder="Cari layanan apa hari ini?"
                        class="w-full border-gray-300 border pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
                </div>
            </div>

            <div class="p-6 overflow-y-auto flex-1 bg-gray-50/50" id="layananCardContainer">
                @php
                    $groupedLayanans = $layanans->groupBy(function($item) { return $item->rootKategori->nama_rootkategori ?? 'Lainnya'; });
                @endphp
                @foreach($groupedLayanans as $rootName => $subs)
                    <div class="mb-6 last:mb-0 layanan-group">
                        <p class="font-bold text-sm text-cyan uppercase tracking-widest mb-3">{{ $rootName }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($subs as $sub)
                                <div class="layanan-card cursor-pointer p-4 bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-cyan transition-all rounded-xl flex flex-col min-h-[90px]"
                                        data-id="{{ $sub->id }}" data-nama="{{ $rootName . ' - ' . $sub->nama_subkategori }}" data-harga="{{ $sub->harga }}" data-durasi="{{ $sub->durasi ?? 0 }}">
                                    <span class="font-medium text-gray-800 mb-auto leading-tight">{{ $sub->nama_subkategori }}</span>
                                    <span class="text-sm font-semibold text-gray-500 mt-4">Rp {{ number_format($sub->harga, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div x-show="isPetugasModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="isPetugasModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="isPetugasModalOpen" @click.away="isPetugasModalOpen = false" x-transition class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg rounded-2xl">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h5 class="font-bold text-lg text-gray-800">Edit Petugas</h5>
                <button @click="isPetugasModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <input type="hidden" id="editPetugasLayananId">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Petugas 1</label>
                    <select class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20" id="petugasSelect1" required>
                        <option value="" selected disabled>Pilih Petugas 1</option>
                        @foreach($petugas as $ptg)
                            <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">{{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Petugas 2 (Opsional)</label>
                    <select class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20" id="petugasSelect2">
                        <option value="" selected>Pilih Petugas 2<option>
                        @foreach($petugas as $ptg)
                            <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">{{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Petugas</label>
                    <input type="text" class="w-full bg-gray-50 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none" id="petugasInput" readonly>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" @click="isPetugasModalOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">Batal</button>
                <button type="button" id="savePetugasBtn" class="px-4 py-2 bg-cyan hover:bg-[#27b9d9] text-white rounded-xl font-medium transition-colors">Simpan</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Konfigurasi Toastr
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-right",
        "timeOut": "4000",
    };

    @if(session('success')) toastr.success("{{ session('success') }}", "Berhasil!"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}", "Gagal!"); @endif
    @if($errors->any())
        @foreach($errors->all() as $error) toastr.error("{{ $error }}", "Validasi Gagal!"); @endforeach
    @endif

    const elements = {
        jamMulaiInput: document.getElementById('jam_mulai'),
        tableBody: document.querySelector('#layananOrderTable tbody'),
        hiddenInputsContainer: document.getElementById('hiddenInputsContainer'),
        editModeInput: document.getElementById('editMode'),
        petugasSelect1: document.getElementById('petugasSelect1'),
        petugasSelect2: document.getElementById('petugasSelect2'),
        petugasInput: document.getElementById('petugasInput'),
        editPetugasLayananId: document.getElementById('editPetugasLayananId'),
        savePetugasBtn: document.getElementById('savePetugasBtn')
    };

    // Dispatch Alpine pengganti "new bootstrap.Modal().hide()" 
    const modalController = {
        hideLayanan: () => window.dispatchEvent(new CustomEvent('close-layanan-modal')),
        showPetugas: () => window.dispatchEvent(new CustomEvent('open-petugas-modal')),
        hidePetugas: () => window.dispatchEvent(new CustomEvent('close-petugas-modal'))
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

    function updateTotalDurasiDanJamSelesai() {
        let totalDurasi = 0;
        document.querySelectorAll('.durasi-input').forEach(input => {
            totalDurasi += parseInt(input.value) || 0;
        });
        document.getElementById('estimasi-durasi').value = totalDurasi + ' Menit';
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
        elements.hiddenInputsContainer.innerHTML = '';
        document.querySelectorAll('#layananOrderTable tbody tr').forEach((row, index) => {
            const idOrderDetailInput = row.querySelector('input[name="id_order_detail[]"]');
            const idOrderDetail = idOrderDetailInput ? idOrderDetailInput.value : '';
            const layananId     = row.dataset.layananId;
            const durasi        = row.querySelector('.durasi-input')?.value || '60';
            const petugasId     = row.dataset.petugasId || '';
            const harga         = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '').trim() || '0';
            const petugasArr    = petugasId.split(',').filter(Boolean);

            elements.hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="id_order_detail[]"  value="${idOrderDetail}">
                <input type="hidden" name="layanans[]"         value="${layananId}">
                <input type="hidden" name="durasi_layanan[]"   value="${durasi}">
                <input type="hidden" name="subtotals[]"        value="${harga}">
            `);

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

    function updateTotalHarga() {
        const rows = elements.tableBody.querySelectorAll('tr');
        let totalHarga = 0;
        rows.forEach(row => {
            const subtotalText = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '');
            totalHarga += parseInt(subtotalText) || 0;
        });
        const diskon = {{ $order->diskon ?? 0 }};
        const totalSetelahDiskon = Math.max(0, totalHarga - diskon);

        const totalHargaInput = document.getElementById('total_harga_input');
        const totalHargaRupiah = document.getElementById('totalHargaRupiah');
        if (totalHargaInput) totalHargaInput.value = 'Rp ' + totalSetelahDiskon;
        if (totalHargaRupiah) totalHargaRupiah.textContent = 'Rp ' + totalSetelahDiskon.toLocaleString('id-ID');
    }

    function updateTableNumbering() {
        const rows = elements.tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => { row.cells[0].textContent = idx + 1; });
    }

    elements.tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete')) {
            if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                e.target.closest('tr').remove();
                updateTableNumbering();
                updateTotalHarga();
                syncHiddenInputsWithTable();
            }
        }
    });
    
    // Fitur Search Layanan
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
                group.style.display = hasVisibleCard ? '' : 'none';
            });
        });
    }

    // Klik Card Tambah Layanan
    document.querySelectorAll('.layanan-card').forEach(card => {
        card.addEventListener('click', function() {
            const isEditMode = elements.editModeInput.value === '1';
            const layananId = this.dataset.id;
            const namaLayanan = this.dataset.nama;
            const harga = this.dataset.harga;

            const existingRow = elements.tableBody.querySelector(`tr[data-layanan-id="${layananId}"]`);
            if (existingRow && !isEditMode) {
                toastr.error("Layanan sudah ada!");
                return;
            }

            const newRow = document.createElement('tr');
            newRow.className = "hover:bg-gray-50/50 transition-colors";
            newRow.dataset.layananId = layananId;
            newRow.innerHTML = `
                <td class="px-6 py-4"></td>
                <td class="px-6 py-4 font-medium text-gray-900">${namaLayanan}</td>
                <td class="px-6 py-4"><input type="number" class="durasi-input w-20 border border-gray-300 px-3 py-1.5 rounded-lg text-sm focus:ring-2 focus:ring-cyan/20 outline-none text-center" name="durasi_layanan[]" min="5" step="5" value="60"></td>
                <td class="px-6 py-4"><span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-gray-400 bg-gray-50">Belum Atur</span></td>
                <td class="px-6 py-4 font-semibold text-cyan">Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="btn-edit-petugas bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg border-0" data-layanan-id="${layananId}">
                            <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <button type="button" class="btn-delete bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg border-0 transition-colors">
                            <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            `;
            
            if (isEditMode && existingRow) { existingRow.replaceWith(newRow); }
            else { elements.tableBody.appendChild(newRow); }
            
            updateTableNumbering();
            addDurasiInputListeners();
            updateTotalDurasiDanJamSelesai();
            updateTotalHarga();

            if(searchInput) searchInput.value = '';
            document.querySelectorAll('.layanan-group, .layanan-card').forEach(el => el.style.display = '');

            syncHiddenInputsWithTable();
            modalController.hideLayanan();
            toastr.success("Layanan berhasil ditambahkan ke tabel", "Berhasil!");
        });
    });

    document.getElementById('petugasSelect1').addEventListener('change', updateNamaPetugas);
    document.getElementById('petugasSelect2').addEventListener('change', updateNamaPetugas);

    function updateNamaPetugas() {
        let nama = '';
        if (elements.petugasSelect1.value) nama += elements.petugasSelect1.options[elements.petugasSelect1.selectedIndex].getAttribute('data-nama');
        if (elements.petugasSelect2.value) nama += ', ' + elements.petugasSelect2.options[elements.petugasSelect2.selectedIndex].getAttribute('data-nama');
        elements.petugasInput.value = nama;
    }

    // Panggil Modal Edit Petugas
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-edit-petugas')) {
            const btn = e.target.closest('.btn-edit-petugas');
            const layananId = e.target.dataset.layananId;
            const currentPetugas = e.target.dataset.currentPetugas || '';
            const currentNamaPetugas = e.target.dataset.currentNamaPetugas || '';
            elements.editPetugasLayananId.value = layananId;

            elements.petugasSelect1.selectedIndex = 0;
            elements.petugasSelect2.selectedIndex = 0;
            elements.petugasInput.value = currentNamaPetugas;

            if (currentPetugas) {
                const petugasArr = currentPetugas.split(',');
                if (petugasArr[0]) elements.petugasSelect1.value = petugasArr[0].trim();
                if (petugasArr[1]) elements.petugasSelect2.value = petugasArr[1].trim();
            }
            updateNamaPetugas();
            modalController.showPetugas();
            syncHiddenInputsWithTable();
        }
    });

    // Save Edit Petugas
    elements.savePetugasBtn.addEventListener('click', function() {
        const layananId = elements.editPetugasLayananId.value;
        const petugasId1 = elements.petugasSelect1.value;
        const petugasId2 = elements.petugasSelect2.value;
        const namaPetugas1 = petugasId1 ? elements.petugasSelect1.options[elements.petugasSelect1.selectedIndex].getAttribute('data-nama') : '';
        const namaPetugas2 = petugasId2 ? elements.petugasSelect2.options[elements.petugasSelect2.selectedIndex].getAttribute('data-nama') : '';
        let displayText = namaPetugas1;
        if (namaPetugas2) displayText += ', ' + namaPetugas2;

        const row = document.querySelector(`tr[data-layanan-id="${layananId}"]`);
        if (row) {
            row.cells[3].innerHTML = `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-blue-50 text-blue-700">${displayText}</span>`;
            row.dataset.petugasId = [petugasId1, petugasId2].filter(Boolean).join(',');
            row.dataset.namaPetugas = displayText;
            const editBtn = row.querySelector('.btn-edit-petugas');
            if (editBtn) {
                editBtn.dataset.currentPetugas = [petugasId1, petugasId2].filter(Boolean).join(',');
                editBtn.dataset.currentNamaPetugas = displayText;
            }
        }
                
        syncHiddenInputsWithTable();
        modalController.hidePetugas();
    });

    // Format Rupiah
    const diskonInput = document.getElementById('diskon_input');
    const diskonDisplay = document.getElementById('diskonRupiah');
    const totalInput = document.getElementById('total_harga_input');
    const totalDisplay = document.getElementById('totalHargaRupiah');

    function formatRupiah(val) {
        let num = parseInt(String(val).replace(/\D/g, '')) || 0;
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    if (diskonInput && totalInput) {
        diskonInput.addEventListener('input', function () {
            diskonDisplay.textContent = formatRupiah(diskonInput.value);
            var diskon = parseInt(diskonInput.value) || 0;
            var hargaSetelahDiskon = Math.max(0, {{ $totalAsli }} - diskon);
            totalInput.value = hargaSetelahDiskon;
            totalDisplay.textContent = formatRupiah(totalInput.value);
        });
    }
});
</script>
@endpush
