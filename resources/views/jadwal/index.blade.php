@extends('layouts.app')

@section('title-content')
<h1>Jadwal</h1>
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
                    <th>Gmaps</th>
                    <th>Catatan</th>
                    <th>Tanggal Pengerjaan</th>
                    <th>Waktu Pengerjaan</th>
                    <th>Durasi</th>
                    <th>Waktu Selesai</th>
                    <th>Nama Petugas</th>
                    <th>Status Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @php
                            $status = strtolower($order->status);
                            $statusColor = match ($status) {
                                'Scheduled' => '#B0DB9C',
                                'Rescheduled' => '#fff000',
                                default => '#dee2e6',
                            };
                        @endphp
                        <span 
                            class="badge px-2 py-2 text-dark" 
                            style="background-color: {{ $statusColor }}; border-radius: 2rem;">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->id_order }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan }}</td>
                    <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                    <td>
                        @if($order->lokasi_gmaps)
                            <a href="{{ $order->lokasi_gmaps }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $order->catatan ?? '-' }}</td>
                    <td>{{ $order->tanggal_pengerjaan }}</td>
                    <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') . ' WIB' : '-' }}</td>
                    <td>{{ $order->orderDetails->sum('durasi_layanan') ? $order->orderDetails->sum('durasi_layanan') . ' menit' : '-' }}</td>
                    <td>
                        @php
                            $jamMulai = $order->jam_pengerjaan ? \Carbon\Carbon::createFromFormat('H:i:s', $order->jam_pengerjaan) : null;
                            $totalDurasi = $order->orderDetails->sum('durasi_layanan');
                            $jamSelesai = ($jamMulai && $totalDurasi) ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') . ' WIB' : '-';
                        @endphp
                        {{ $jamSelesai }}
                    </td>
                    <td>{{ $order->orderDetails->pluck('petugas.nama_petugas')->unique()->implode(', ') ?: '-' }}</td>
                    <td>{{ $order->metode_pembayaran ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('jadwal.reschedule', $order->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning table-action-button">
                                    Re-schedule
                                </button>
                            </form>
                            <form action="{{ route('jadwal.selesai', $order->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success table-action-button">
                                    Selesai
                                </button>
                            </form>
                            <form action="{{ route('jadwal.destroy', $order->id_order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger table-action-button">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="text-center">Belum ada data jadwal</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
