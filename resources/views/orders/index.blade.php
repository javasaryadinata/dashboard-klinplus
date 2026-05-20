@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Order</h3>
@endsection

@section('content')
<div x-data="{ isModalOpen: false }">
    <div class="flex flex-row md:flex-row justify-between items-end md:items-center gap-4 mb-2">

        <div class="flex flex-row items-center gap-2 w-full md:max-w-xs z-20">
            <form method="GET" action="{{ route('orders.index') }}" autocomplete="off" class="relative w-full" onsubmit="event.preventDefault();">
                <div class="absolute inset-y-0 left-0 pl-3 flex text-gray-400 items-center pointer-events-none">
                    <x-lucide-search class="h-4 w-4 stroke-current stroke-[1.5]" />
                </div>
                <input type="text" id="searchInput" name="search" placeholder="Cari" value="{{ request('search') }}"
                    class="w-full h-8 bg-white border border-gray-200 pl-8 py-2 rounded-lg text-gray-600 text-sm focus:ring-1 focus:ring-gray-300 outline-none transition-all shadow-sm">
                <button type="button" id="clearSearchBtn" class="absolute inset-y-0 right-0 m-1.5 px-0.5 rounded-sm flex items-center hover:bg-gray-200 text-gray-400 transition-colors {{ request('search') ? '' : 'hidden' }}">
                    <x-lucide-x class="h-4 w-4 stroke-current stroke-[1.6]" />
                </button>
            </form>
        </div>
        <button @click="isModalOpen = true; setTimeout(isiAlamatOtomatis, 100)" class="bg-sky-400 hover:bg-sky-500 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors shadow-sm flex items-center gap-1 whitespace-nowrap w-full md:w-auto justify-start">
            <x-lucide-file-plus-corner class="h-4 w-4 stroke-current stroke-[2]" />
            Tambah order
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4" id="order-success-alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto w-full">
        <div class="table-wrapper min-w-[800px] border border-gray-200 rounded-lg">
            <table class="order-table w-full text-left">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id_order', 'direction' => request('sort') == 'id_order' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-2 hover:text-gray-800 transition-colors">
                                Order
                                @if (request('sort') == 'id_order')
                                    @if (request('direction') == 'asc')
                                        <x-lucide-chevron-up class="w-3 h-3 stroke-current stroke-[3]" />
                                    @else
                                        <x-lucide-chevron-down class="w-3 h-3 stroke-current stroke-[3]" />
                                    @endif
                                @else
                                    <x-lucide-chevrons-up-down class="w-3 h-3 stroke-current stroke-[3]" />
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'pelanggan', 'direction' => request('sort') == 'pelanggan' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-2 hover:text-gray-800 transition-colors">
                                Pelanggan
                                @if (request('sort') == 'pelanggan')
                                    @if (request('direction') == 'asc')
                                        <x-lucide-chevron-up class="w-3 h-3 stroke-current stroke-[3]" />
                                    @else
                                        <x-lucide-chevron-down class="w-3 h-3 stroke-current stroke-[3]" />                                     
                                    @endif
                                @else
                                    <x-lucide-chevrons-up-down class="w-3 h-3 stroke-current stroke-[3]" />
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'tanggal_pengerjaan', 'direction' => request('sort') == 'tanggal_pengerjaan' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-2 hover:text-gray-800 transition-colors">
                                Tanggal Pengerjaan
                                @if (request('sort') == 'tanggal_pengerjaan')
                                    @if (request('direction') == 'asc')
                                        <x-lucide-chevron-up class="w-3 h-3 stroke-current stroke-[3]" />
                                    @else
                                        <x-lucide-chevron-down class="w-3 h-3 stroke-current stroke-[3]" />                                     
                                    @endif
                                @else
                                    <x-lucide-chevrons-up-down class="w-3 h-3 stroke-current stroke-[3]" />
                                @endif
                            </a>
                        </th>
                        <th>Waktu</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Status Order</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-gray-200 divide-y">
                    @forelse($orders as $order)
                    @php
                        $totalDurasi = $order->orderDetails->sum('durasi_layanan');
                        $jamMulai = \Carbon\Carbon::createFromFormat('H:i:s', $order->jam_pengerjaan);
                        $jamSelesai = $totalDurasi ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') . ' WIB' : '-';
                    @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->id_order }}</td>
                        <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ $order->tanggal_pengerjaan ? \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') : '-' }}</td>
                        <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                        <td>{{ $order->pelanggan->kota->nama_kota ?? '-' }}</td>
                        <td>
                            @php
                                $badgeColor = match($order->status) {
                                    'Request' => 'badge-secondary',
                                    'Scheduled' => 'badge-warning',
                                    'Selesai' => 'badge-success',
                                    'Canceled'  => 'badge-error',
                                    default     => 'badge-ghost',
                                };
                            @endphp
                            <div class="badge badge-soft {{ $badgeColor }} text-xs px-4">
                                {{ ucfirst($order->status) }}
                            </div>
                        </td>
                        <td class="relative text-center" x-data="{ openAction: false }">
                            <button @click="openAction = !openAction" class="p-1 text-gray-500 hover:bg-gray-100 rounded-md transition-colors focus:outline-none" title="Actions">
                                <x-lucide-ellipsis class="w-4 h-4 stroke-current stroke-[2]" />
                            </button>  
                            
                            <div x-show="openAction" @click.away="openAction = false" x-transition.opacity.duration.200ms style="display: none;"
                                class="absolute right-10 top-2 w-44 bg-white border border-gray-200 p-1 rounded-lg shadow-lg z-50 text-left overflow-hidden">
                                
                                <a href="{{ route('orders.detail', $order->id_order) }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-xs text-gray-600 font-medium hover:bg-gray-100 transition-colors">
                                    <x-lucide-square-arrow-out-up-right class="w-4 h-4 stroke-current stroke-[1.6]" />
                                    Lihat Detail Order
                                </a>

                                @if($order->pelanggan && $order->pelanggan->telp_pelanggan)
                                    @php
                                        $waNumber = preg_replace('/[^0-9]/', '', $order->pelanggan->telp_pelanggan);
                                        if (substr($waNumber, 0, 1) == '0') $waNumber = '62' . substr($waNumber, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-gray-600 font-medium hover:bg-gray-100 transition-colors">
                                        <x-lucide-message-circle-more class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Hubungi Pelanggan
                                    </a>
                                @else
                                    <div class="flex items-center px-2 py-1.5 gap-2rounded-md text-xs text-gray-600 font-medium cursor-not-allowed" title="Nomor WA tidak tersedia">
                                        <x-lucide-message-circle-x class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Tidak ada WA
                                    </div>
                                @endif

                                <a href="{{ route('orders.invoicePdf', $order->id_order) }}" target="_blank" class="flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-gray-600 font-medium hover:bg-gray-100 transition-colors">
                                    <x-lucide-file-down class="w-4 h-4 stroke-current stroke-[1.6]" />
                                    Download Invoice
                                </a>

                                <div class="h-px bg-gray-200 my-1 mx-1"></div>
                                <form action="{{ route('orders.approve', $order->id_order) }}" method="POST" class="w-full m-0">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-gray-600 font-medium hover:bg-gray-100 transition-colors text-left">
                                        <x-lucide-check class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Jadwalkan Order
                                    </button>
                                </form>

                                <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" class="w-full m-0" onsubmit="return confirm('Batalkan order ini?')">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-gray-600 font-medium hover:bg-gray-100 transition-colors text-left">
                                        <x-lucide-x class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Batalkan Order
                                    </button>
                                </form>

                                <form action="{{ route('orders.destroy', $order->id_order) }}" method="POST" class="w-full m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permanen order ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-red-500 hover:bg-red-50 transition-colors text-left">
                                        <x-lucide-trash-2 class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Hapus
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
    // # LIVE SEARCH AJAX
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const tableWrapper = document.querySelector('.table-wrapper');
    let debounceTimer;

    if (searchInput && tableWrapper) {
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = ''; // Kosongkan input
                clearSearchBtn.classList.add('hidden'); // Sembunyikan tombol X
                searchInput.dispatchEvent(new Event('input')); // Trigger AJAX pencarian ulang otomatis
            });
        }

        searchInput.addEventListener('input', function() {
            
            if (this.value.length > 0) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }

            clearTimeout(debounceTimer);
            
            debounceTimer = setTimeout(() => {
                tableWrapper.style.transition = 'opacity 0.3s';
                tableWrapper.style.opacity = '0.5';

                const url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set('search', this.value);
                } else {
                    url.searchParams.delete('search');
                }

                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableContent = doc.querySelector('.table-wrapper').innerHTML;
                    
                    tableWrapper.innerHTML = newTableContent;
                    tableWrapper.style.opacity = '1';
                    window.history.pushState({}, '', url);
                })
                .catch(err => {
                    console.error('Gagal mengambil data pencarian:', err);
                    tableWrapper.style.opacity = '1';
                });
            }, 500); 
        });
    }

    // # ALERT AUTO HIDE
    const alert = document.getElementById('order-success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }

    // # AUTO-FILL ALAMAT PELANGGAN
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

    // # TAMBAH & HAPUS LAYANAN
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

    // # KALKULASI DISKON & TOTAL HARGA
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