@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Pembayaran</h3>
@endsection

@section('content')
<div>
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('pembayaran.index') }}" autocomplete="off" class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <input type="text" name="search" placeholder="Cari" value="{{ request('search') }}" 
                class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
            
            @if(request('search'))
                <a href="{{ route('pembayaran.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="pembayaran-success-alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto w-full">
        <div class="min-w-[1000px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">No</th>
                        <th class="px-4 py-3 font-semibold">ID Order</th>
                        <th class="px-4 py-3 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 font-semibold w-48">Alamat</th>
                        <th class="px-4 py-3 font-semibold w-48">Layanan</th>
                        <th class="px-4 py-3 font-semibold whitespace-nowrap">
                            <a href="{{ route('pembayaran.index', array_merge(request()->except('page'), [
                                'sort' => ($sort === 'asc' ? 'desc' : 'asc'),
                                'search' => $search
                            ])) }}" class="flex items-center gap-1 hover:text-cyan transition-colors">
                            Tanggal
                                @if($sort === 'asc')
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 font-semibold">Diskon</th>
                        <th class="px-4 py-3 font-semibold">Total Harga</th>
                        <th class="px-4 py-3 font-semibold">Status Pembayaran</th>
                        <th class="px-4 py-3 font-semibold text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $order->id_order }}</td>
                        <td class="px-4 py-3">{{ $order->pelanggan->nama_pelanggan }}</td>
                        <td class="px-4 py-3 text-xs leading-relaxed text-gray-500">{{ $order->alamat_lokasi ?? '-' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            @php
                                $layananList = $order->orderDetails->map(function($detail) {
                                    $root = $detail->layananSubkategori->rootKategori->nama_rootkategori ?? '';
                                    $sub = $detail->layananSubkategori->nama_subkategori ?? '';
                                    return trim($root . ' - ' . $sub, ' -');
                                })->unique()->implode(', ');
                            @endphp
                            {{ $layananList ?: '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $order->tanggal_pengerjaan ? \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') : '-'  }}</td>
                        <td class="px-4 py-3 text-red-500 font-medium whitespace-nowrap">
                            {{ $order->diskon ? 'Rp ' . number_format($order->diskon, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-cyan whitespace-nowrap">
                            @php
                                $totalHarga = $order->orderDetails->sum('harga');
                            @endphp
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $metode = $order->metode_pembayaran ? ucfirst($order->metode_pembayaran) : '-';
                                $tipe = $order->tipe_pembayaran ? ucfirst($order->tipe_pembayaran) : '-';
                            @endphp
                            <span class="px-2 py-1 bg-yellow-50 text-yellow-700 border border-yellow-200 rounded text-[11px] font-bold uppercase tracking-wider shadow-sm">{{ $metode }} - {{ $tipe }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center gap-1 justify-center">
                                <a href="{{ route('orders.show', ['id_order' => $order->id_order, 'ref' => 'pembayaran']) }}" class="p-1.5 text-cyan hover:bg-cyan/10 rounded-md transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <a href="{{ route('orders.invoicePdf', $order->id_order) }}" target="_blank" class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-md transition-colors" title="Cetak Invoice">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>

                                @if($order->metode_pembayaran !== 'Lunas')
                                <form action="{{ route('pembayaran.setLunas', $order->id_order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-green-500 hover:bg-green-50 rounded-md transition-colors" title="Tandai Lunas" onclick="return confirm('Apakah pembayaran sisa tagihan sudah diterima dan order ini akan dilunasi?')">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </button>
                                </form>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded uppercase tracking-wider ml-1">Lunas</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">Belum ada data pembayaran tagihan (DP) yang menunggu pelunasan.</td>
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
        const alert = document.getElementById('pembayaran-success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 3000); // Alert hilang dalam 3 detik
        }
    });
</script>
@endpush

