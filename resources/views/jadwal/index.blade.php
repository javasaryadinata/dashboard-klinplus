@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Jadwal</h3>
@endsection

@section('content')
<div x-data="{
    isRescheduleOpen: false,
    actionUrl: '',
    orderId: '',
    tanggal: '',
    jam: '',
    catatan: '',

    openRescheduleModal(url, id, tgl, jm, cttn) {
        this.actionUrl = url;
        this.orderId = id;
        this.tanggal = tgl;
        this.jam = jm;
        this.catatan = cttn;
        this.isRescheduleOpen = true;
    }
}">

    <div class="flex justify-between items-center mb-4">
        <form method="GET" action="{{ route('jadwal.index') }}" autocomplete="off" class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <input type="text" name="search" placeholder="Cari" value="{{ request('search') }}"
                class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
                
            @if(request('search'))
                <a href="{{ route('jadwal.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="jadwal-success-alert">
        {{ session('success') }}   
    </div>
    @endif

    {{-- Container Table --}}
    <div class="overflow-x-auto w-full">
        <div class="min-w-[1200px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">ID Order</th>
                        <th class="px-4 py-3 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 font-semibold w-48">Alamat</th>
                        <th class="px-4 py-3 font-semibold">Gmaps</th>
                        <th class="px-4 py-3 font-semibold w-40">Catatan</th>
                        <th class="px-4 py-3 font-semibold whitespace-nowrap">
                            <a href="{{ route('jadwal.index', array_merge(request()->except('page'), ['sort' => ($sort === 'asc' ? 'desc' : 'asc'), 'search' => $search])) }}"
                                class="flex items-center gap-1 hover:text-cyan transition-colors">
                                Tanggal
                                @if($sort === 'asc')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-semibold">Waktu</th>
                        <th class="px-4 py-3 font-semibold whitespace-nowrap">
                            <a href="{{ route('jadwal.index', array_merge(request()->except('page'), [
                                'sort_durasi' => ($sortDurasi === 'asc' ? 'desc' : 'asc'),
                                'sort' => $sort,
                                'search' => $search
                            ])) }}" class="flex items-center gap-1 hover:text-cyan transition-colors">
                                Durasi
                                @if($sortDurasi === 'asc')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @elseif($sortDurasi === 'desc')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @else
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-semibold">Selesai</th>
                        <th class="px-4 py-3 font-semibold">Petugas</th>
                        <th class="px-4 py-3 font-semibold">Status Pembayaran</th>
                        <th class="px-4 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($jadwals as $jadwal)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm whitespace-nowrap"
                                style="background:{{ $jadwal->order->status === 'Scheduled' ? '#16C47F' : ($jadwal->order->status === 'Rescheduled' ? '#FFD65A' : ($jadwal->order->status === 'Selesai' ? '#3FD6CB' : '#ddd')) }};">
                                {{ ucfirst($jadwal->order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $jadwal->order->id_order ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $jadwal->order && $jadwal->order->pelanggan ? $jadwal->order->pelanggan->nama_pelanggan : '-' }}</td>
                        <td class="px-4 py-3 text-xs leading-relaxed text-gray-500">{{ $jadwal->order->alamat_lokasi ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if(isset($jadwal->order) && $jadwal->order->lokasi_gmaps)
                                <a href="{{ $jadwal->order->lokasi_gmaps }}" target="_blank" class="text-blue-500 hover:text-blue-700 flex items-center gap-1 hover:underline text-xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Lihat
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 italic">{{ $jadwal->order->catatan ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $jadwal->order->tanggal_pengerjaan ? \Carbon\Carbon::parse($jadwal->order->tanggal_pengerjaan)->format('d-m-Y') : '-' }}</td>
                        <td class="px-4 py-3 font-semibold text-cyan">
                            @php
                                $jamPengerjaan = $jadwal->order->jam_pengerjaan ?? null;
                            @endphp
                            {{ $jamPengerjaan ? \Carbon\Carbon::parse($jamPengerjaan)->format('H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                            @php
                                $orderDetails = (isset($jadwal->order) && $jadwal->order->orderDetails) ? $jadwal->order->orderDetails : collect();
                            @endphp
                            {{ $orderDetails->sum('durasi_layanan') ? $orderDetails->sum('durasi_layanan') . ' menit' : '-' }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-600">
                            @php
                                $jamMulai = (isset($jadwal->order) && $jadwal->order->jam_pengerjaan) ? \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->order->jam_pengerjaan) : null;
                                $totalDurasi = $orderDetails->sum('durasi_layanan');
                                $jamSelesai = ($jamMulai && $totalDurasi) ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') : '-';
                            @endphp
                            {{ $jamSelesai }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if($orderDetails->count())
                                {{ $orderDetails->flatMap->petugas->pluck('nama_petugas')->unique()->implode(', ') }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">{{ $jadwal->order->metode_pembayaran ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1 justify-center">
                                @if(isset($jadwal->order) && \Carbon\Carbon::parse($jadwal->order->tanggal_pengerjaan)->isFuture())
                                <a href="{{ route('orders.show', $jadwal->id_order) }}" class="p-1.5 text-cyan hover:bg-cyan/10 rounded-md transition-colors" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                @endif

                                <a href="{{ route('jadwal.workingOrder', $jadwal->id_order) }}" target="_blank" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-md transition-colors" title="Cetak Surat Tugas">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>

                                <button type="button" @click="openRescheduleModal('{{ route('jadwal.doReschedule', $jadwal->id_order) }}', '{{ $jadwal->id_order }}', '{{ $jadwal->order->tanggal_pengerjaan }}', '{{ $jadwal->order->jam_pengerjaan }}', '{{ $jadwal->order->catatan ?? '' }}')"
                                    class="p-1.5 text-yellow-500 hover:bg-yellow-50 rounded-md transition-colors" title="Reschedule">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </button>

                                <form action="{{ route('jadwal.selesai', $jadwal->id_order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-green-500 hover:bg-green-50 rounded-md transition-colors" title="Tandai Selesai">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </button>
                                </form>
                                
                                <form action="{{ route('orders.cancel', $jadwal->order->id_order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition-colors" onclick="return confirm('Batalkan jadwal ini?')" title="Batal Order">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </button>
                                </form>

                                <form action="{{ route('jadwal.destroy', $jadwal->id_order) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors" onsubmit="return confirm('Hapus jadwal permanen?')" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-8 text-gray-500">Belum ada data jadwal</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Reschedule -->
    <div x-show="isRescheduleOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isRescheduleOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isRescheduleOpen" @click.away="isRescheduleOpen = false" x-transition
                class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg rounded-2xl">

                <form method="POST" :action="actionUrl">
                    @csrf

                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-2xl">
                        <h5 class="font-bold text-lg text-gray-800">
                            Reschedule Order <span class="text-cyan" x-text="orderId"></span>
                        </h5>
                        <button type="button" @click="isRescheduleOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 bg-gray-50/30">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Baru</label>
                                <input type="date" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white"
                                    name="tanggal_pengerjaan" :value="tanggal" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Baru</label>
                                <input type="time" class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white"
                                    name="jam_pengerjaan" :value="jam" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Reschedule</label>
                            <textarea class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white"
                                name="alasan_reschedule" rows="2" placeholder="Tulis alasan perubahan jadwal..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white text-gray-500"
                                name="catatan" rows="2" :value="catatan"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isRescheduleOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium transition-colors shadow-sm">Simpan Reschedule</button>
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
        const alert = document.getElementById('jadwal-success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    });
</script>
@endpush