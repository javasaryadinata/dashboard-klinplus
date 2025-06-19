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
                    <td>{{ $jadwal->id_order }}</td>
                    <td>{{ $jadwal->nama_pelanggan }}</td>
                    <td>{{ $jadwal->alamat }}</td>
                    <td>
                        @if($jadwal->gmaps)
                            <a href="{{ $jadwal->gmaps }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $jadwal->catatan ?? '-' }}</td>
                    <td>{{ $jadwal->tanggal_pengerjaan }}</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_pengerjaan)->format('H:i') }} WIB</td>
                    <td>{{ $jadwal->durasi }} menit</td>
                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB</td>
                    <td>{{ $jadwal->nama_petugas ?? '-' }}</td>
                    <td>{{ $jadwal->status_pembayaran }}</td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('jadwal.show', $jadwal->id_order) }}" class="btn btn-info table-action-button">
                                Layanan
                            </a>
                            <form action="{{ route('jadwal.selesai', $jadwal->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success table-action-button">
                                    Selesai
                                </button>
                            </form>
                            <form action="{{ route('jadwal.reschedule', $jadwal->id_order) }}" method="GET">
                                <button type="submit" class="btn btn-warning table-action-button">
                                    Re-schedule
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
    </div>
</div>
@endsection
