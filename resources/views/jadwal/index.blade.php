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
                @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @php
                            $status = strtolower($jadwal->status);
                            $statusColor = match ($status) {
                                'Scheduled' => '#B0DB9C',
                                'Rescheduled' => '#fff000',
                                default => '#dee2e6',
                            };
                        @endphp
                        <span 
                            class="badge px-2 py-2 text-dark" 
                            style="background-color: {{ $statusColor }}; border-radius: 2rem;">
                            {{ ucfirst($jadwal->status) }}
                        </span>
                    </td>
                    <td>{{ $jadwal->order->id_order ?? '-' }}</td>
                    <td>{{ $jadwal->order && $jadwal->order->pelanggan ? $jadwal->order->pelanggan->nama_pelanggan : '-' }}</td>
                    <td>{{ $jadwal->order->alamat_lokasi ?? '-' }}</td>
                    <td>
                        @if(isset($jadwal->order) && $jadwal->order->lokasi_gmaps)
                            <a href="{{ $jadwal->order->lokasi_gmaps }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $jadwal->order->catatan ?? '-' }}</td>
                    <td>{{ $jadwal->order->tanggal_pengerjaan ?? '-' }}</td>
                    <td>
                        @php
                            $jamPengerjaan = $jadwal->order->jam_pengerjaan ?? null;
                        @endphp
                        {{ $jamPengerjaan ? \Carbon\Carbon::parse($jamPengerjaan)->format('H:i') . ' WIB' : '-' }}
                    </td>
                    <td>
                        @php
                            $orderDetails = (isset($jadwal->order) && $jadwal->order->orderDetails) ? $jadwal->order->orderDetails : collect();
                        @endphp
                        {{ $orderDetails->sum('durasi_layanan') ? $orderDetails->sum('durasi_layanan') . ' menit' : '-' }}
                    </td>
                    <td>
                        @php
                            $jamMulai = (isset($jadwal->order) && $jadwal->order->jam_pengerjaan) ? \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->order->jam_pengerjaan) : null;
                            $totalDurasi = $orderDetails->sum('durasi_layanan');
                            $jamSelesai = ($jamMulai && $totalDurasi) ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') . ' WIB' : '-';
                        @endphp
                        {{ $jamSelesai }}
                    </td>
                    <td>
                        @if($orderDetails->count())
                            {{ $orderDetails->flatMap->petugas->pluck('nama_petugas')->unique()->implode(', ') }}
                        @else
                            {{ $jadwal->nama_petugas ?? '-' }}
                        @endif
                    </td>
                    <td>{{ $jadwal->status_pembayaran ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <button type="button" class="btn btn-warning table-action-button" data-bs-toggle="modal" data-bs-target="#modalReschedule-{{ $jadwal->id_order }}">
                                Re-schedule
                            </button>
                            <form action="{{ route('jadwal.selesai', $jadwal->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success table-action-button">
                                    Selesai
                                </button>
                            </form>
                            <form action="{{ route('jadwal.destroy', $jadwal->id_order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
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

        @foreach($jadwals as $jadwal)
        <!-- Modal Reschedule -->
        <div class="modal fade" id="modalReschedule-{{ $jadwal->id_order }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <form method="POST" action="{{ route('jadwal.doReschedule', $jadwal->id_order) }}">
                    @csrf
                    <div class="modal-header">
                    <h5 class="modal-title">Reschedule Order {{ $jadwal->id_order }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-2">
                        <label>Tanggal Baru</label>
                        <input type="date" class="form-control" name="tanggal_pengerjaan" value="{{ $jadwal->order->tanggal_pengerjaan }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Jam Baru</label>
                        <input type="time" class="form-control" name="jam_pengerjaan" value="{{ $jadwal->order->jam_pengerjaan }}" required>
                    </div>
                    <div class="mb-2">
                        <label>Alasan Reschedule</label>
                        <textarea class="form-control" name="alasan_reschedule"></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Catatan</label>
                        <textarea class="form-control" name="catatan">{{ $jadwal->order->catatan }}</textarea>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Reschedule</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
