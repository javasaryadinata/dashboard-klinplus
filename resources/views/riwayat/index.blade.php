@extends('layouts.app')

@section('title-content')
<h1>Riwayat Order</h1>
@endsection

@section('content')
<div class="container-table">
    <div class="table-wrapper">
        <table class="staf-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>ID Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Layanan</th>
                    <th>Petugas</th>
                    <th>Order Pengganti</th>
                    <th>Order Awal</th>
                    <th>Alasan Reschedule</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="badge px-2 py-2 text-dark" style="background-color:
                            @if(strtolower($order->status) === 'rescheduled') #ffe066
                            @elseif(strtolower($order->status) === 'selesai') #B0DB9C
                            @else #eee @endif;
                            border-radius: 2rem;">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->id_order }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                    <td>{{ $order->tanggal_pengerjaan ?? '-' }}</td>
                    <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') . ' WIB' : '-' }}</td>
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
