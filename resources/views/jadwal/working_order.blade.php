@php
    use Carbon\Carbon;

    $jamMulai = Carbon::parse($order->jam_pengerjaan);
    $totalDurasi = $order->orderDetails->sum('durasi_layanan');
    $jamSelesai = $jamMulai->copy()->addMinutes($totalDurasi);
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Working Order - {{ $order->id_order }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            margin: 30px;
        }
        p {
            margin: 2px 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>WORKING ORDER</h2>
    <p>ID Order : <strong>{{ $order->id_order }}</strong></p>
    <p>Nama Pelanggan : {{ $order->pelanggan->nama_pelanggan ?? '-' }}</p>
    <p>Telp : {{ $order->pelanggan->telp_pelanggan ?? '-' }}</p>
    <p>Alamat : {{ $order->alamat_lokasi }}</p>
    <p>Google Maps : <a href={{ $order->lokasi_gmaps }}>Lihat</a></p>
    <p>Catatan : {{ $order->catatan }}</p>
    <p style="text-align: right;">Tanggal : {{ Carbon::parse($order->tanggal_pengerjaan)->translatedFormat('d F Y') }}</p>
    <p style="text-align: right;">Jam Mulai : {{ Carbon::parse($order->jam_pengerjaan)->translatedFormat('H:i') . ' WIB' }}</p>
    <p style="text-align: right;">Jam Selesai : {{ $jamSelesai->translatedFormat('H:i') . ' WIB' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Layanan</th>
                <th>Durasi</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $i => $detail)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-' }} - {{ $detail->layananSubkategori->nama_subkategori ?? '-' }}</td>
                <td>{{ $detail->durasi_layanan }} menit</td>
                <td>{{ $detail->petugas->pluck('nama_petugas')->implode(', ') ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:30px;">Note :</p>
</body>
</html>
