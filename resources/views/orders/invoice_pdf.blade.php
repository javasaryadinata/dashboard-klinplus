<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->id_order }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { margin-bottom: 20px; }
        .header h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px;}
        th, td { border: 1px solid #ddd; padding: 7px;}
        th { background: #f2f2f2; }
        .text-end { text-align: right; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
        <p>ID Order: <b>{{ $order->id_order }}</b><br>
           Pelanggan: <b>{{ $order->pelanggan->nama_pelanggan }}</b><br>
           Alamat: {{ $order->alamat_lokasi }}<br>
           Tanggal Pengerjaan: {{ \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Durasi</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
            <tr>
                <td>{{ $detail->layananSubkategori->nama_subkategori ?? '-' }}</td>
                <td>{{ $detail->durasi_layanan ?? 0 }} menit</td>
                <td class="text-end">Rp {{ number_format($detail->harga ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-end">Diskon</td>
                <td class="text-end">Rp {{ number_format($order->diskon ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-end total">Total</td>
                <td class="text-end total">Rp {{ number_format($order->total_harga - ($order->diskon ?? 0), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <p style="margin-top:30px;"><i>Terima kasih telah menggunakan layanan kami.</i></p>
</body>
</html>
