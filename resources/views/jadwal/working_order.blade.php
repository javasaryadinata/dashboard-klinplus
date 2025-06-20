<!DOCTYPE html>
<html>
<head>
    <title>Working Order - {{ $order->id_order }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>WORKING ORDER</h2>
    <p><strong>ID Order:</strong> {{ $order->id_order }}</p>
    <p><strong>Nama Pelanggan:</strong> {{ $order->pelanggan->nama_pelanggan ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $order->alamat_lokasi }}</p>
    <p><strong>Tanggal:</strong> {{ $order->tanggal_pengerjaan }}</p>
    <p><strong>Jam:</strong> {{ $order->jam_pengerjaan }}</p>

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

    <p style="margin-top:30px;">Tanda tangan petugas: ____________________</p>
</body>
</html>
