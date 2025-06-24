@extends('layouts.app')

@section('title-content')
<h1>Riwayat Order</h1>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center" style="gap:16px;">
    <form method="GET" action="{{ route('riwayat.index') }}" autocomplete="off">
        <div class="input-group">
            <input 
                type="text" 
                class="form-control" 
                name="search" 
                placeholder="Cari"
                value="{{ request('search') }}" 
                id="search-input"
                style="max-width: 400px;"
            >

            @if(request('search'))
                <a href="{{ route('riwayat.index') }}" class="btn-clear-search" id="btn-clear-search">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
            {{-- <button class="btn btn-new" type="submit">
                <i class="bi bi-search"></i> Cari
            </button> --}}
        </div>
    </form>
    <form method="GET" action="{{ route('riwayat.index') }}" id="filter-status-form" class="filter-riwayat">
        <label for="filter-status-select" style="margin-right:8px;white-space:nowrap;font-weight:500;">Filter</label>
        <select name="status" class="form-select" style="max-width:200px; display:inline-block;" onchange="document.getElementById('filter-status-form').submit()">
            <option value="">Semua</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Rescheduled" {{ request('status') == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
            <option value="Canceled" {{ request('status') == 'Canceled' ? 'selected' : '' }}>Canceled</option>
        </select>
    </form>
</div>
<div class="container-table">
    <div class="table-wrapper">
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>ID Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>
                        Tanggal
                        <a href="{{ route('riwayat.index', array_merge(request()->except('page'), [
                            'sort' => ($sort === 'asc' ? 'desc' : 'asc'),
                            'search' => $search,
                            'status' => request('status')
                        ])) }}" style="text-decoration:none; color:inherit;">
                            @if($sort === 'asc')
                                <i class="bi bi-arrow-up"></i>
                            @else
                                <i class="bi bi-arrow-down"></i>
                            @endif
                        </a>
                    </th>
                    <th>Waktu</th>
                    <th>Layanan</th>
                    <th>Petugas</th>
                    <th>Order Pengganti</th>
                    <th>Order Awal</th>
                    <th>Alasan</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span
                            class="badge" 
                            style="background:{{ $order->status === 'Scheduled' ? '#16C47F' : ($order->status === 'Rescheduled' ? '#FFD65A' : ($order->status === 'Selesai' ? '#00CC66' : '#ddd')) }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->id_order }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                    <td>{{ $order->tanggal_pengerjaan ?? '-' }}</td>
                    <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') : '-' }}</td>
                    <td>
                        @foreach($order->orderDetails as $detail)
                            {{ ($detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-') . ' - ' . ($detail->layananSubkategori->nama_subkategori ?? '-') }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($order->orderDetails as $detail)
                            {{ $detail->petugas->pluck('nama_petugas')->implode(', ') }}<br>
                        @endforeach
                    </td>
                    <td>
                        {{-- Jika order ini hasil reschedule dari order sebelumnya --}}
                        @if($order->reschedule_from)
                            <a href="{{ route('orders.show', $order->reschedule_from) }}">
                                {{ $order->reschedule_from }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{-- Jika ada order baru hasil reschedule dari order ini --}}
                        @php
                            $orderPengganti = \App\Models\Order::where('reschedule_from', $order->id_order)->first();
                        @endphp
                        @if($orderPengganti)
                            <a href="{{ route('orders.show', $orderPengganti->id_order) }}">
                                {{ $orderPengganti->id_order }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $order->alasan_reschedule ?? '-' }}</td>
                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="text-center">Belum ada data riwayat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
