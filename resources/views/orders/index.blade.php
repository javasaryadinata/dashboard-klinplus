@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Order</h3>
@endsection

@section('content')
<div x-data="{ isModalOpen: false }">
    <div class="d-flex justify-end mb-3">
        <button @click="isModalOpen = true; setTimeout(isiAlamatOtomatis, 100)" class="bg-cyan hover:bg-[#27b9d9] text-white px-3 py-2 rounded-md text-sm font-normal transition-colors shadow-sm">
            Tambah Order
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4" id="order-success-alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto w-full">
        <div class="table-wrapper min-w-[800px]">
            <table class="order-table w-full text-left">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Order</th>
                        <th>Nama Pelanggan</th>
                        <th>
                            <a href="{{ route('orders.index', ['sort' => ($sort === 'desc' ? 'asc' : 'desc')]) }}" class="flex items-center gap-1">
                                @if($sort === 'desc')
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 2xl:h-5 lucide lucide-arrow-down-wide-narrow-icon lucide-arrow-down-wide-narrow"><path d="m3 16 4 4 4-4"/><path d="M7 20V4"/><path d="M11 4h10"/><path d="M11 8h7"/><path d="M11 12h4"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 2xl:h-4 lucide lucide-move-up-icon lucide-move-up"><path d="M8 6L12 2L16 6"/><path d="M12 2V22"/></svg>
                                @endif
                                Tanggal Pengerjaan
                            </a>
                        </th>
                        <th>Waktu</th>
                        <th>Alamat</th>
                        <th>Total Harga</th>
                        <th>Status Order</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $totalDurasi = $order->orderDetails->sum('durasi_layanan');
                        $jamMulai = \Carbon\Carbon::createFromFormat('H:i:s', $order->jam_pengerjaan);
                        $jamSelesai = $totalDurasi ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') . ' WIB' : '-';
                    @endphp
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->id_order }}</td>
                        <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ $order->tanggal_pengerjaan ? \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') : '-' }}</td>
                        <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                        <td>
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="px-4 py-1 rounded-full text-xs font-medium text-white"
                                style="background:{{ $order->status === 'Request' ? '#0096FF' : ($order->status === 'Scheduled' ? '#B0DB9C' : ($order->status === 'Selesai' ? '#3FD6CB' : '#ddd')) }};">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $metode = $order->metode_pembayaran ? ucfirst($order->metode_pembayaran) : '-';
                                $tipe = $order->tipe_pembayaran ? ucfirst($order->tipe_pembayaran) : '-';
                            @endphp
                            {{ $metode }} / {{ $tipe }}
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <a href="{{ route('orders.detail', $order->id_order) }}" class="p-1.5 text-cyan hover:bg-cyan/10 rounded-md transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @if($order->pelanggan && $order->pelanggan->telp_pelanggan)
                                    @php
                                        $waNumber = preg_replace('/[^0-9]/', '', $order->pelanggan->telp_pelanggan);
                                        if (substr($waNumber, 0, 1) == '0') $waNumber = '62' . substr($waNumber, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="p-1.5 text-green-500 hover:bg-green-50 rounded-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada WA</span>
                                @endif
                                <a href="{{ route('orders.invoicePdf', $order->id_order) }}" target="_blank" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-md transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                                </a>
                                <form action="{{ route('orders.approve', $order->id_order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-green-500 hover:bg-green-50 rounded-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" onsubmit="return confirm('Batalkan order ini?')">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('orders.destroy', $order->id_order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus order ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:bg-gray-100 hover:text-red-500 rounded-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-6 text-gray-500">Belum ada data order</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Tambah Order Modal -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div  x-show="isModalOpen" @click.away="isModalOpen = false" x-transition class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">

                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h4 class="text-lg font-bold text-gray-900" id="tambahOrderModalLabel">Tambah Order Baru</h4>
                    <button @click="isModalOpen = false" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form id="formTambahOrder" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pelanggan</label>
                            <select class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all" id="id_pelanggan" name="id_pelanggan" required>
                                <option value="" selected disabled>Pilih Pelanggan</option>
                                @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id_pelanggan }}" data-alamat="{{ $pelanggan->alamat_lokasi }}" data-gmaps="{{ $pelanggan->lokasi_gmaps ?? '' }}">
                                    {{ $pelanggan->nama_pelanggan }} - {{ $pelanggan->alamat_lokasi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none text-gray-500" id="input_alamat_lokasi" name="alamat_lokasi" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gmaps</label>
                                <input type="text" class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none text-gray-500" id="input_lokasi_gmaps" name="lokasi_gmaps" readonly>
                            </div>
                        </div>
    
                        <div class="mb-3">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all" id="input_catatan" name="catatan" rows="2"></textarea>
                        </div>
    
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Layanan</label>
                            <div id="layanan-container" class="space-y-3">
                                <div class="layanan-row flex gap-2 items-center">
                                    <select name="layanan_subkategori[]" class="flex-1 border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all layanan-select" required>
                                        <option value="" disabled selected>Pilih Layanan</option>
                                        @foreach(\App\Models\LayananSubkategori::with('rootKategori')->get() as $layanan)
                                            <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}">
                                                {{ $layanan->rootKategori->nama_rootkategori ?? '' }} - {{ $layanan->nama_subkategori }} (Rp{{ number_format($layanan->harga,0,',','.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-remove-layanan text-red-500 hover:bg-red-50 p-2 rounded-lg" style="display:none;">
                                        <svg class="w-5 h-5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn-add-layanan mt-3 text-cyan font-medium text-sm hover:underline">Tambah Layanan</button>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Diskon</label>
                                <input type="text" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none" id="input_kode" name="kode" maxlength="20">
                                <div class="mt-1 text-xs" id="diskon-msg"></div>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Harga</label>
                                <input type="number" class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none text-gray-500 font-bold" id="input_total_harga" name="total_harga" readonly required>
                            </div>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengerjaan</label>
                                <input type="date" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none" id="input_tanggal_pengerjaan" name="tanggal_pengerjaan" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Pengerjaan</label>
                                <input type="time" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none" id="input_jam_pengerjaan" name="jam_pengerjaan" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-medium transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-cyan text-white rounded-xl hover:bg-[#27b9d9] font-medium transition-colors">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('order-success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }

    const pelangganSelect = document.getElementById('id_pelanggan');
    const alamatInput = document.getElementById('input_alamat_lokasi');
    const gmapsInput = document.getElementById('input_lokasi_gmaps');

    window.isiAlamatOtomatis = function() {
        const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            alamatInput.value = selectedOption.getAttribute('data-alamat') || '';
            gmapsInput.value = selectedOption.getAttribute('data-gmaps') || '';
        }
    }
    pelangganSelect.addEventListener('change', isiAlamatOtomatis);

    const layananContainer = document.getElementById('layanan-container');
    const btnAddLayanan = document.querySelector('.btn-add-layanan');

    function updateRemoveButtons() {
        const rows = layananContainer.querySelectorAll('.layanan-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.btn-remove-layanan');
            btn.style.display = rows.length > 1 ? '' : 'none';
        });
    }
    
    btnAddLayanan.addEventListener('click', function() {
        const row = layananContainer.querySelector('.layanan-row');
        const clone = row.cloneNode(true);
        clone.querySelector('.layanan-select').selectedIndex = 0;
        layananContainer.appendChild(clone);
        setHargaOtomatis(clone);
        updateRemoveButtons();
        hitungTotalHarga();
    });

    layananContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-layanan')) {
            e.target.closest('.layanan-row').remove();
            updateRemoveButtons();
            hitungTotalHarga();
        }
    });

    const diskonList = {
        {!! $promos->map(function($promo) {
            return '"'.strtoupper($promo->kode).'": '.(int)$promo->diskon;
        })->join(',') !!}
    };

    const kodeDiskonInput = document.getElementById('input_kode');
    const totalHargaInput = document.getElementById('input_total_harga');
    let diskonAktif = 0;
    let diskonMsg = document.getElementById('diskon-msg');
    if (!diskonMsg) {
        diskonMsg = document.createElement('div');
        diskonMsg.id = 'diskon-msg';
        diskonMsg.style.fontSize = '0.9em';
        kodeDiskonInput.parentNode.appendChild(diskonMsg);
    }

    function hitungTotalHarga() {
        let total = 0;
        layananContainer.querySelectorAll('.layanan-select').forEach(select => {
            const harga = select.options[select.selectedIndex]?.getAttribute('data-harga');
            total += parseInt(harga) || 0;
        });
        let totalSetelahDiskon = total;
        if (diskonAktif > 0) {
            totalSetelahDiskon = Math.max(0, total - diskonAktif);
        }
        totalHargaInput.value = totalSetelahDiskon;
    }

    kodeDiskonInput.addEventListener('input', function() {
        const kode = kodeDiskonInput.value.trim().toUpperCase();
        if (kode && diskonList[kode]) {
            diskonAktif = diskonList[kode];
            diskonMsg.textContent = `Kode valid : -Rp${diskonAktif.toLocaleString('id-ID')}`;
            diskonMsg.style.color = 'green';
        } else if (kode) {
            diskonAktif = 0;
            diskonMsg.textContent = 'Kode tidak valid';
            diskonMsg.style.color = 'red';
        } else {
            diskonAktif = 0;
            diskonMsg.textContent = '';
        }
        hitungTotalHarga();
    });

    function setHargaOtomatis(row) {
        const select = row.querySelector('.layanan-select');
        select.addEventListener('change', function() { hitungTotalHarga(); });
    }

    document.querySelectorAll('.layanan-row').forEach(row => setHargaOtomatis(row));
    updateRemoveButtons();
    hitungTotalHarga();
});
</script>
@endpush