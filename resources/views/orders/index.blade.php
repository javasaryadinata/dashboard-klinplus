    @extends('layouts.app')

    @section('title-content')
    <h3 class="font-semibold text-3xl 2xl:text-4xl">Order</h3>
    @endsection

    @section('content')
    <div x-data="{ isModalOpen: {{ $errors->any() ? 'true' : 'false' }} }"
        @keydown.escape.window="if(isModalOpen) { isModalOpen = false; resetFormOrder(); }">

        <div class="flex flex-row md:flex-row justify-between items-end md:items-center gap-4 mb-2">

            <div class="flex flex-row items-center gap-2 w-full md:max-w-xs z-20">
                <form method="GET" action="{{ route('orders.index') }}" autocomplete="off" class="relative w-full" onsubmit="event.preventDefault();">
                    <div class="absolute inset-y-0 left-0 pl-3 flex text-gray-400 items-center pointer-events-none">
                        <x-lucide-search class="h-4 w-4 stroke-current stroke-[1.5]" />
                    </div>
                    <input type="text" id="searchInput" name="search" placeholder="Cari" value="{{ request('search') }}"
                        class="w-full h-8 bg-white border border-gray-200 pl-8 py-2 rounded-lg text-gray-600 text-sm focus:ring-1 focus:ring-gray-300 outline-none transition-all shadow-sm">
                    <button type="button" id="clearSearchBtn" class="absolute inset-y-0 right-0 m-1.5 px-0.5 rounded-sm flex items-center hover:bg-gray-100 text-gray-400 transition-colors {{ request('search') ? '' : 'hidden' }}">
                        <x-lucide-x class="h-4 w-4 stroke-current stroke-[1.6]" />
                    </button>
                </form>
            </div>
            <button @click="isModalOpen = true; setTimeout(isiAlamatOtomatis, 100)" class="bg-sky-400 hover:bg-sky-500 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors shadow-sm flex items-center gap-1 whitespace-nowrap w-full md:w-auto justify-start">
                <x-lucide-file-plus-corner class="h-4 w-4 stroke-current stroke-[2]" />
                Tambah order
            </button>
        </div>

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

                                    <button type="button"
                                            @click="$dispatch('open-delete-modal', { url: '{{ route('orders.destroy', $order->id_order) }}' })"
                                            class="w-full flex items-center px-2 py-1.5 gap-2 rounded-md text-xs text-red-500 hover:bg-red-50 transition-colors text-left">
                                        <x-lucide-trash-2 class="w-4 h-4 stroke-current stroke-[1.6]" />
                                        Hapus
                                    </button>
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
        
        <!-- MODAL TAMBAH ORDER -->         
        <template x-teleport="body">
            <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div x-show="isModalOpen" @click="isModalOpen = false; resetFormOrder()" x-transition.opacity class="fixed inset-0 backdrop-brightness-75 backdrop-blur-xs transition-opacity cursor-pointer"></div>
            
                <div class="flex min-h-full items-center justify-center p-4 pointer-events-none">
                    <div x-show="isModalOpen" x-transition class="relative transform rounded-2xl bg-white text-left shadow-2xl transition-all w-lg pointer-events-auto">
                        
                        <div class=" border-gray-100 px-4 py-2 flex justify-between items-center">
                            <h4 class="pt-2 text-lg font-semibold text-sky-400" id="tambahOrderModalLabel">Tambah Order Baru</h4>
                            <button @click="isModalOpen = false; resetFormOrder()" type="button" class="rounded-md hover:bg-gray-100 text-gray-400 transition-colors">
                                <x-lucide-x class="h-4 w-4 m-0.5 stroke-current stroke-[1.6]" />
                            </button>
                        </div>
                        <form id="formTambahOrder" method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            <div class="px-4 py-2 max-h-[70vh] overflow-y-auto">
                                <div class="mb-4" x-data="{
                                    open: false,
                                    search: '',
                                    selectedName: '',
                                    selectedId: '',
                                    pelanggans: [
                                        @foreach($pelanggans as $p)
                                        { id: '{{ $p->id_pelanggan }}', nama: '{{ addslashes($p->nama_pelanggan) }}', alamat: '{{ addslashes($p->alamat_lokasi) }}', gmaps: '{{ addslashes($p->lokasi_gmaps ?? '') }}' },
                                        @endforeach
                                    ],
                                    get filtered() {
                                        if (this.search === '') return this.pelanggans;
                                        const keyword = this.search.toLowerCase();
                                        return this.pelanggans.filter(p => p.nama.toLowerCase().includes(keyword) || p.alamat.toLowerCase().includes(keyword));
                                    },
                                    selectPelanggan(p) {
                                        this.selectedId = p.id;
                                        this.selectedName = p.nama + ' - ' + p.alamat;
                                        this.open = false;
                                        this.search = '';
                                        document.getElementById('input_alamat_lokasi').value = p.alamat;
                                        document.getElementById('input_lokasi_gmaps').value = p.gmaps;
                                    },
                                }" @reset-modal.window="selectedId = ''; selectedName = ''; search = ''; open = false;">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan <span class="text-red-500">*</span></label>
            
                                    <input type="hidden" name="id_pelanggan" :value="selectedId" required>
                                    <div class="relative w-full min-w-0" @click.away="open = false; search = ''">
                                        <div @click="open = true; $nextTick(() => $el.querySelector('input').focus())"
                                            class="w-full h-9 bg-white border border-gray-300 px-2 rounded-lg text-sm flex items-center justify-between cursor-pointer transition-all hover:bg-gray-50 min-w-0"
                                            :class="open ? ' border-1 border-sky-400' : ''">
                                            <span x-show="!open && selectedId" class="truncate block flex-1 min-w-0 text-left pr-2 text-gray-700" x-text="selectedName"></span>
                                            <span x-show="!open && !selectedId" class="truncate block flex-1 min-w-0 text-left pr-2 text-gray-400">Pilih Pelanggan...</span>
                                            <input type="text" x-show="open" x-model="search" placeholder="Cari nama atau alamat..."
                                                class="flex-1 min-w-0 w-full bg-transparent outline-none text-gray-700 placeholder-gray-400"
                                                @click.stop
                                                @keydown.escape="open = false; search = ''" />
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <button type="button" x-show="open && search.length > 0" @click.stop="search = ''" class="rounded-sm hover:bg-gray-200 text-gray-400 transition-colors">
                                                    <x-lucide-x class="w-3.5 h-3.5 stroke-current stroke-[1.8]" />
                                                </button>
                                                <x-lucide-chevron-down class="w-4 h-4 text-gray-400 transition-transform duration-200" x-bind:class="open ? 'rotate-180' : ''" />
                                            </div>
                                        </div>
                                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                            class="absolute left-0 top-full z-[60] w-full p-2 shadow-xl bg-white border border-gray-100 rounded-xl mt-1">
                                            <ul class="menu menu-compact max-h-56 overflow-y-auto flex-nowrap p-0 gap-1">
                                                <template x-for="p in filtered" :key="p.id">
                                                    <li>
                                                        <a href="#" @click.prevent="selectPelanggan(p)" class="flex flex-col items-start px-3 py-2 hover:bg-slate-50 rounded-lg cursor-pointer">
                                                            <span class="font-medium text-gray-600 text-sm" x-text="p.nama"></span>
                                                            <span class="text-xs text-gray-500 w-full truncate" x-text="p.alamat"></span>
                                                        </a>
                                                    </li>
                                                </template>
                                                <li x-show="filtered.length === 0">
                                                    <span class="text-center italic text-gray-400 py-4 text-sm pointer-events-none">Pelanggan tidak ditemukan</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <input type="text" class="w-full h-9 bg-gray-50 border border-gray-200 px-1 rounded-lg text-sm outline-none text-gray-500 cursor-text" id="input_alamat_lokasi" name="alamat_lokasi" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea class="w-full min-h-22 border border-gray-300 px-2 rounded-lg text-sm focus:border-1 focus:border-sky-400 outline-none transition-all" id="input_catatan" name="catatan" rows="2"></textarea>
                                    <input type="hidden" id="input_lokasi_gmaps" name="lokasi_gmaps">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengerjaan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <x-lucide-calendar class="h-4 w-4" />
                                            </div>
                                            <input type="text"
                                                name="tanggal_pengerjaan"
                                                id="input_tanggal_pengerjaan"
                                                required
                                                placeholder="Pilih Tanggal..."
                                                x-data
                                                x-init="flatpickr($el, {
                                                    dateFormat: 'Y-m-d',
                                                    altInput: true,
                                                    altFormat: 'd F Y',
                                                    locale: 'id',
                                                    minDate: 'today'
                                                })"
                                                class="w-full h-9 bg-white border border-gray-300 pl-9 pr-3 rounded-lg text-sm  focus:border-1 focus:border-sky-400 outline-none transition-all cursor-pointer">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pengerjaan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <x-lucide-clock class="h-4 w-4" />
                                            </div>
                                            <input type="text"
                                                name="jam_pengerjaan"
                                                id="input_jam_pengerjaan"
                                                required
                                                placeholder="Pilih Jam..."
                                                x-data
                                                x-init="flatpickr($el, {
                                                    enableTime: true,
                                                    noCalendar: true,
                                                    dateFormat: 'H:i',
                                                    time_24hr: true
                                                })"
                                                class="w-full h-9 bg-white border border-gray-300 pl-9 pr-3 rounded-lg text-sm  focus:border-1 focus:border-sky-400 outline-none transition-all cursor-pointer">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4" x-data="{
                                    rows: [ { id: Date.now(), selectedId: '', selectedName: '', harga: 0, search: '', open: false } ],
                                    masterLayanan: [
                                        @foreach(\App\Models\LayananSubkategori::with('rootKategori')->get() as $layanan)
                                        {
                                            id: '{{ $layanan->id }}',
                                            nama: '{{ addslashes($layanan->rootKategori->nama_rootkategori ?? '') }} - {{ addslashes($layanan->nama_subkategori) }}',
                                            harga: {{ $layanan->harga }},
                                            textHarga: 'Rp{{ number_format($layanan->harga, 0, ',', '.') }}'
                                        },
                                        @endforeach
                                    ],
                                    getFiltered(keyword) {
                                        if (keyword === '') return this.masterLayanan;
                                        const lowerKeyword = keyword.toLowerCase();
                                        return this.masterLayanan.filter(l => l.nama.toLowerCase().includes(lowerKeyword));
                                    },
                                    selectLayanan(row, layanan) {
                                        row.selectedId = layanan.id;
                                        row.selectedName = layanan.nama + ' (' + layanan.textHarga + ')';
                                        row.harga = layanan.harga;
                                        row.open = false;
                                        row.search = '';
                                        this.updateTotal();
                                    },
                                    addRow() {
                                        const lastRow = this.rows[this.rows.length - 1];
                                        if (lastRow && lastRow.selectedId === '') return;
                                        this.rows.push({ id: Date.now(), selectedId: '', selectedName: '', harga: 0, search: '', open: false });
                                    },
                                    removeRow(index) {
                                        if (this.rows.length <= 1) return;
                                        this.rows.splice(index, 1);
                                        this.updateTotal();
                                    },
                                    updateTotal() {
                                        let total = this.rows.reduce((sum, row) => sum + row.harga, 0);
                                        window.dispatchEvent(new CustomEvent('update-layanan-total', { detail: { total: total } }));
                                    },
                                    get isAddDisabled() {
                                        return this.rows.some(row => row.selectedId === '');
                                    }
                                }" @reset-modal.window="rows = [ { id: Date.now(), selectedId: '', selectedName: '', harga: 0, search: '', open: false } ]; updateTotal();">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Layanan <span class="text-red-500">*</span></label>
            
                                    <div class="space-y-3">
                                        <template x-for="(row, index) in rows" :key="row.id">
                                            <div class="flex gap-2 items-center w-full">
                                                <input type="hidden" name="layanan_subkategori[]" :value="row.selectedId" required>
                                                <div class="relative flex-1 min-w-0" @click.away="row.open = false; row.search = ''">
                                                    <div @click="row.open = true; $nextTick(() => $el.querySelector('input').focus())"
                                                        class="w-full h-9 bg-white border border-gray-300 px-3 rounded-lg text-sm flex items-center justify-between cursor-pointer transition-all hover:bg-gray-50 min-w-0"
                                                        x-bind:class="row.open ? ' border-1 border-sky-400' : ''">
                                                        <span x-show="!row.open && row.selectedId" class="truncate block flex-1 min-w-0 text-left pr-2 text-gray-700" x-text="row.selectedName"></span>
            
                                                        <span x-show="!row.open && !row.selectedId" class="truncate block flex-1 min-w-0 text-left pr-2 text-gray-400">Pilih Layanan...</span>
            
                                                        <input type="text" x-show="row.open" x-model="row.search" placeholder="Cari layanan..."
                                                            class="flex-1 min-w-0 w-full bg-transparent outline-none text-gray-700 placeholder-gray-400"
                                                            @click.stop
                                                            @keydown.escape="row.open = false; row.search = ''" />
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            <button type="button" x-show="row.open && row.search.length > 0" @click.stop="row.search = ''" class="rounded-sm hover:bg-gray-200 text-gray-400 transition-colors">
                                                                <x-lucide-x class="w-3.5 h-3.5 stroke-current stroke-[1.8]" />
                                                            </button>
                                                            <x-lucide-chevron-down class="w-4 h-4 text-gray-400 transition-transform duration-200" x-bind:class="row.open ? 'rotate-180' : ''" />
                                                        </div>
                                                    </div>
                                                    <div x-show="row.open" x-transition.opacity.duration.200ms style="display: none;"
                                                        class="absolute left-0 top-full z-[60] w-full p-2 shadow-xl bg-white border border-gray-100 rounded-xl mt-1">
            
                                                        <ul class="menu menu-compact max-h-56 overflow-y-auto flex-nowrap p-0 gap-1">
                                                            <template x-for="l in getFiltered(row.search)" :key="l.id">
                                                                <li>
                                                                    <a href="#" @click.prevent="selectLayanan(row, l)" class="flex flex-col items-start px-3 py-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                                                                        <span class="font-medium text-gray-700 text-sm" x-text="l.nama"></span>
                                                                        <span class="text-xs text-sky-500 font-normal mt-0.5" x-text="l.textHarga"></span>
                                                                    </a>
                                                                </li>
                                                            </template>
                                                            <li x-show="getFiltered(row.search).length === 0">
                                                                <span class="text-center italic text-gray-400 py-4 text-sm pointer-events-none">Layanan tidak ditemukan</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
            
                                                <button type="button" x-show="rows.length > 1 && row.selectedId !== ''" @click="removeRow(index)" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors shrink-0" title="Hapus Layanan">
                                                    <x-lucide-trash-2 class="w-4 h-4 stroke-current stroke-[1.2]" />
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" @click="addRow()" x-bind:disabled="isAddDisabled" class="border px-2 py-1 mt-2 rounded-md text-gray-400 font-normal text-xs hover:bg-sky-50 transition-colors inline-flex items-center gap-1.5" x-bind:class="isAddDisabled ? 'border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed opacity-60' : 'border-sky-200 text-sky-400 hover:bg-sky-50 cursor-pointer'">
                                            <x-lucide-plus class="w-3.5 h-3.5 stroke-[2.5]" /> Tambah Layanan
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Diskon</label>
                                        <input type="text" class="w-full h-8 border border-gray-300 px-2 rounded-lg text-sm focus:border-1 focus:border-sky-400 outline-none" id="input_kode" name="kode" maxlength="20">
                                        <div class="mt-1 text-xs" id="diskon-msg"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                                        <input type="hidden" id="input_total_harga" name="total_harga" required>
                                        <input type="text" class="w-full h-8 bg-gray-50 border border-gray-200 px-2 rounded-lg text-sm outline-none text-green-400 font-normal" id="display_total_harga" readonly placeholder="Rp 0">
                                    </div>
                                </div>
                            </div>
                            <div class="border-t-2 border-gray-200  bg-gray-50 px-4 py-3 flex justify-end gap-2 rounded-b-2xl">
                                <button type="button" @click="isModalOpen = false; resetFormOrder()" class="px-3 py-1 bg-gray-200 text-sm text-gray-500 rounded-md hover:bg-gray-300 font-medium transition-colors shadow-sm">Batal</button>
                                <button type="submit" class="px-3 py-1 bg-sky-400 text-white text-sm rounded-md hover:bg-sky-500 font-medium transition-colors shadow-sm">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <div x-data="{ isDeleteModalOpen: false, deleteUrl: '' }" 
            @open-delete-modal.window="isDeleteModalOpen = true; deleteUrl = $event.detail.url">
            
            <template x-teleport="body">
                <div x-show="isDeleteModalOpen" 
                    style="display: none;" 
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    
                    <div x-show="isDeleteModalOpen" 
                        x-transition.opacity 
                        @click="isDeleteModalOpen = false" 
                        class="fixed inset-0 bg-black/50 backdrop-blur-sm cursor-pointer"></div>
                    
                    <div x-show="isDeleteModalOpen" 
                        x-transition 
                        class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl relative z-10">
                        
                        <h3 class="text-lg font-bold text-gray-800">Hapus Order</h3>
                        <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus data ini?</p>
                        
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="isDeleteModalOpen = false" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-200 transition-colors">Batal</button>
                            <form :action="deleteUrl" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors shadow-sm">Hapus Permanen</button>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // # KONFIGURASI TOASTR
        toastr.opstions = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "preventDuplicates": true
        };

        const isBackNavigation = performance.getEntriesByType("navigation")[0]?.type === "back_forward";

        if (!isBackNavigation) {
            // # 1. TRIGGER TOASTR UNTUK ERROR VALIDASI
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Gagal");
                @endforeach
            @endif
            // # 2. TRIGGER TOASTR UNTUK ERROR SERVER
            @if (session('error'))
                toastr.error("{{ session('error') }}", "Sistem Error");
            @endif
            // # 3. TRIGGER TOASTR UNTUK SUKSES
            @if (session('success'))
                toastr.success("{{ session('success') }}", "Berhasil!");
            @endif
        }

        // # LIVE SEARCH AJAX
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const tableWrapper = document.querySelector('.table-wrapper');
        let debounceTimer;

        if (searchInput && tableWrapper) {
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearchBtn.classList.add('hidden');
                    searchInput.dispatchEvent(new Event('input'));
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

        // # KALKULASI DISKON & TOTAL HARGA
        const diskonList = {
            {!! $promos->map(function($promo) {
                return '"'.strtoupper($promo->kode).'": '.(int)$promo->diskon;
            })->join(',') !!}
        };

        const kodeDiskonInput = document.getElementById('input_kode');
        const totalHargaInput = document.getElementById('input_total_harga');
        let diskonAktif = 0;
        let totalLayanan = 0;
        let diskonMsg = document.getElementById('diskon-msg');
        
        if (!diskonMsg) {
            diskonMsg = document.createElement('div');
            diskonMsg.id = 'diskon-msg';
            diskonMsg.style.fontSize = '0.9em';
            kodeDiskonInput.parentNode.appendChild(diskonMsg);
        }

        function hitungTotalHarga() {
            let totalSetelahDiskon = totalLayanan;
            if (diskonAktif > 0) {
                totalSetelahDiskon = Math.max(0, totalLayanan - diskonAktif);
            }

            totalHargaInput.value = totalSetelahDiskon;

            const displayTotalInput = document.getElementById('display_total_harga');
            if (displayTotalInput) {
                if (totalSetelahDiskon > 0) {
                    displayTotalInput.value = 'Rp ' + totalSetelahDiskon.toLocaleString('id-ID');
                } else {
                    displayTotalInput.value = 'Rp 0';
                }
            }
        }

        window.addEventListener('update-layanan-total', function(e) {
            totalLayanan = e.detail.total;
            hitungTotalHarga();
        });

        kodeDiskonInput.addEventListener('input', function() {
            const kode = kodeDiskonInput.value.trim().toUpperCase();
            if (kode && diskonList[kode]) {
                diskonAktif = diskonList[kode];
                diskonMsg.textContent = `Kode valid`;
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

        hitungTotalHarga();

        // # VALIDASI TOMBOL TAMBAH
        function validateSubmitButton() {
            const btnTambah = document.querySelector('#formTambahOrder button[type="submit"]');
            if (!btnTambah) return;

            const pelanggan = document.querySelector('input[name="id_pelanggan"]')?.value;
            const tanggal = document.getElementById('input_tanggal_pengerjaan')?.value;
            const jam = document.getElementById('input_jam_pengerjaan')?.value;

            const layanans = Array.from(document.querySelectorAll('input[name="layanan_subkategori[]"]'));
            const adaLayanan = layanans.some(input => input.value !== '');

            if (pelanggan && tanggal && jam && adaLayanan) {
                btnTambah.disabled = false;
                btnTambah.classList.remove('opacity-50', 'cursor-not-allowed');
                btnTambah.classList.add('hover:bg-sky-500', 'cursor-pointer');
            } else {
                btnTambah.disabled = true;
                btnTambah.classList.add('opacity-50', 'cursor-not-allowed');
                btnTambah.classList.remove('hover:bg-sky-500', 'cursor-pointer');
            }
        }

        document.addEventListener('change', (e) => { if(e.target.closest('#formTambahOrder')) validateSubmitButton(); });
        document.addEventListener('input', (e) => { if(e.target.closest('#formTambahOrder')) validateSubmitButton(); });
        document.addEventListener('click', () => setTimeout(validateSubmitButton, 100)); // Nangkap klik Flatpickr/Alpine
        window.addEventListener('update-layanan-total', validateSubmitButton);
        window.addEventListener('reset-modal', () => setTimeout(validateSubmitButton, 100));

        const initWatcher = setInterval(() => {
            if (document.getElementById('formTambahOrder')) {
                validateSubmitButton();
                clearInterval(initWatcher);
            }
        }, 100);
        setTimeout(() => clearInterval(initWatcher), 5000);

        if (formOrder) {
            validateSubmitButton();

            formOrder.addEventListener('change', validateSubmitButton);
            formOrder.addEventListener('input', validateSubmitButton);

            formOrder.addEventListener('click', () => setTimeout(validateSubmitButton, 100));
        }

        window.addEventListener('update-layanan-total', validateSubmitButton);
    });

    // RESET MODAL ORDER
    window.resetFormOrder = function() {
        // 1. Reset input native HTML
        const form = document.getElementById('formTambahOrder');
        if (form) form.reset();

        // 2. Reset Flatpickr
        const tgl = document.getElementById('input_tanggal_pengerjaan');
        const jam = document.getElementById('input_jam_pengerjaan');
        if (tgl && tgl._flatpickr) tgl._flatpickr.clear();
        if (jam && jam._flatpickr) jam._flatpickr.clear();

        // 3. Reset state Alpine.js
        window.dispatchEvent(new CustomEvent('reset-modal'));

        // 4. Reset diskon dan perhitungan ulang total harga
        const kodeDiskon = document.getElementById('input_kode');
        if (kodeDiskon) {
            kodeDiskon.value = '';
            kodeDiskon.dispatchEvent(new Event('input'));
        }
    }
    </script>
    @endpush