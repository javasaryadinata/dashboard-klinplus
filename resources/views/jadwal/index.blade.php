@extends('layouts.app')

@section('title-content')
<h1>Jadwal</h1>
@endsection

@section('content')
<form method="GET" action="{{ route('jadwal.index') }}" autocomplete="off">
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
            <a href="{{ route('jadwal.index') }}" class="btn-clear-search" id="btn-clear-search">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
        {{-- <button class="btn btn-new" type="submit">
            <i class="bi bi-search"></i> Cari
        </button> --}}
    </div>
</form>
<div class="container-table">
    <div class="table-wrapper">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>ID Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Gmaps</th>
                    <th>Catatan</th>
                    <th>
                        Tanggal
                        <a href="{{ route('jadwal.index', array_merge(request()->except('page'), ['sort' => ($sort === 'asc' ? 'desc' : 'asc'), 'search' => $search])) }}" style="text-decoration:none; color:inherit;">
                            @if($sort === 'asc')
                                <i class="bi bi-arrow-up"></i>
                            @else
                                <i class="bi bi-arrow-down"></i>
                            @endif
                        </a>
                    </th>
                    <th>Waktu</th>
                    <th>
                        Durasi
                        <a href="{{ route('jadwal.index', array_merge(request()->except('page'), [
                            'sort_durasi' => ($sortDurasi === 'asc' ? 'desc' : 'asc'),
                            'sort' => $sort,
                            'search' => $search
                        ])) }}" style="text-decoration:none; color:inherit;">
                            @if($sortDurasi === 'asc')
                                <i class="bi bi-arrow-up"></i>
                            @elseif($sortDurasi === 'desc')
                                <i class="bi bi-arrow-down"></i>
                            @else
                                <i class="bi bi-arrow-down-up"></i>
                            @endif
                        </a>
                    </th>
                    <th>Selesai</th>
                    <th>Petugas</th>
                    <th>Status Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span 
                            class="badge" 
                            style="background:{{ $jadwal->order->status === 'Scheduled' ? '#16C47F' : ($jadwal->order->status === 'Rescheduled' ? '#FFD65A' : ($jadwal->order->status === 'Selesai' ? '#3FD6CB' : '#ddd')) }};">
                            {{ ucfirst($jadwal->order->status) }}
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
                    <td>{{ $jadwal->order->tanggal_pengerjaan ? \Carbon\Carbon::parse($jadwal->order->tanggal_pengerjaan)->format('d-m-Y') : '-' }}</td>
                    <td>
                        @php
                            $jamPengerjaan = $jadwal->order->jam_pengerjaan ?? null;
                        @endphp
                        {{ $jamPengerjaan ? \Carbon\Carbon::parse($jamPengerjaan)->format('H:i') : '-' }}
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
                            $jamSelesai = ($jamMulai && $totalDurasi) ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') : '-';
                        @endphp
                        {{ $jamSelesai }}
                    </td>
                    <td>
                        @if($orderDetails->count())
                            {{ $orderDetails->flatMap->petugas->pluck('nama_petugas')->unique()->implode(', ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $jadwal->order->metode_pembayaran ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            @if(isset($jadwal->order) && \Carbon\Carbon::parse($jadwal->order->tanggal_pengerjaan)->isFuture())
                            <a href="{{ route('orders.show', $jadwal->id_order) }}" class="btn-action btn-detail">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            @endif
                            <a href="{{ route('jadwal.workingOrder', $jadwal->id_order) }}" class="btn-action btn-invoice" target="_blank">
                                <i class="bi bi-file-text-fill"></i>
                            </a>
                            <button type="button" class="btn-action btn-reschedule" data-bs-toggle="modal" data-bs-target="#modalReschedule-{{ $jadwal->id_order }}">
                                <i class="bi bi-calendar3"></i>
                            </button>
                            <form action="{{ route('jadwal.selesai', $jadwal->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-action btn-setuju">
                                    <i class="bi bi-check-square-fill"></i>
                                </button>
                            </form>
                            <form action="{{ route('orders.cancel', $jadwal->order->id_order) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Batalkan jadwal ini?')">
                                    <i class="bi bi-x-square-fill"></i>
                                </button>
                            </form>
                            <form action="{{ route('jadwal.destroy', $jadwal->id_order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-hapus">
                                    <i class="bi bi-trash-fill"></i>
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
                    <button type="button" class="btn btn-back" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-save">Simpan Reschedule</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
