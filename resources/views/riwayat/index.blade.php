@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Riwayat Order</h3>
@endsection

@section('content')
<div>
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('riwayat.index') }}" autocomplete="off" class="relative w-full max-w-md">
             @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <input type="text" name="search" placeholder="Cari ID order, pelanggan..." value="{{ request('search') }}"
                class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">

            @if(request('search'))
                <a href="{{ route('riwayat.index', array_merge(request()->except('search'))) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>

        <form method="GET" action="{{ route('riwayat.index') }}" id="filter-status-form" class="flex items-center gap-3 w-full md:w-auto">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <label for="filter-status-select" class="font-semibold text-gray-700 text-sm whitespace-nowrap">Filter Status:</label>
            <select name="status" id="filter-status-select"
                    class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 cursor-pointer shadow-sm min-w-[150px]"
                    onchange="document.getElementById('filter-status-form').submit()">
                <option value="">Semua</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Rescheduled" {{ request('status') == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="riwayat-success-alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto w-full">
        <div class="min-w-[1400px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">ID Order</th>
                        <th class="px-4 py-3 font-semibold w-40">Nama Pelanggan</th>
                        <th class="px-4 py-3 font-semibold w-48">Alamat</th>
                        <th class="px-4 py-3 font-semibold whitespace-nowrap">
                            <a href="{{ route('riwayat.index', array_merge(request()->except('page'), [
                                'sort' => ($sort === 'asc' ? 'desc' : 'asc'),
                                'search' => $search,
                                'status' => request('status')
                            ])) }}" class="flex items-center gap-1 hover:text-cyan transition-colors">
                                Tanggal
                                @if($sort === 'asc')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-semibold">Waktu</th>
                        <th class="px-4 py-3 font-semibold w-48">Layanan</th>
                        <th class="px-4 py-3 font-semibold w-32">Petugas</th>
                        <th class="px-4 py-3 font-semibold w-24">Order Pengganti</th>
                        <th class="px-4 py-3 font-semibold w-24">Order Awal</th>
                        <th class="px-4 py-3 font-semibold w-32">Alasan</th>
                        <th class="px-4 py-3 font-semibold">Harga</th>
                        <th class="px-4 py-3 font-semibold">Diskon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-sm whitespace-nowrap"
                                style="background:{{ $order->status === 'Scheduled' ? '#16C47F' : ($order->status === 'Rescheduled' ? '#FFD65A' : ($order->status === 'Selesai' ? '#00CC66' : '#FF4C4C')) }};">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            <a href="{{ route('orders.show', ['id_order' => $order->id_order, 'ref' => 'riwayat']) }}" class="text-cyan hover:underline">
                                {{ $order->id_order }}
                            </a>
                        </td>
                        <td class="px-4 py-3">{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs leading-relaxed text-gray-500">{{ $order->alamat_lokasi ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $order->tanggal_pengerjaan ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold text-cyan">
                            {{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600 leading-tight">
                            @if($order->orderDetails->count())
                                <ul class="list-disc pl-3 space-y-1">
                                @foreach($order->orderDetails as $detail)
                                    {{ ($detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-') . ' - ' . ($detail->layananSubkategori->nama_subkategori ?? '-') }}<br>
                                @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            @if($order->orderDetails->count())
                                <ul class="list-disc pl-3 space-y-1">
                                @foreach($order->orderDetails as $detail)
                                    <li>{{ $detail->petugas->pluck('nama_petugas')->implode(', ') ?: 'Belum diatur' }}</li>
                                @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($order->reschedule_from)
                                <a href="{{ route('orders.show', ['id_order' => $order->reschedule_from, 'ref' => 'riwayat']) }}" class="inline-flex items-center gap-1 text-xs font-medium text-yellow-600 hover:text-yellow-700 bg-yellow-50 px-2 py-1 rounded border border-yellow-200">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                                    {{ $order->reschedule_from }}
                                </a>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $orderPengganti = \App\Models\Order::where('reschedule_from', $order->id_order)->first();
                            @endphp
                            @if($orderPengganti)
                                <a href="{{ route('orders.show', ['id_order' => $orderPengganti->id_order, 'ref' => 'riwayat']) }}" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-2 py-1 rounded border border-indigo-200">
                                    {{ $orderPengganti->id_order }}
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs italic text-gray-500">{{ $order->alasan_reschedule ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-red-500 font-medium whitespace-nowrap">Rp {{ $order->diskon ? '- Rp ' . number_format($order->diskon, 0, ',', '.') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-8 text-gray-500">Belum ada data riwayat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('riwayat-success-alert');
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