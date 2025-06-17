<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Booking</title>
    <style>
        body {
            font-family: 'Poopin', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #333;
        }
        .container {
            max-width: 640px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #059baa;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 24px;
        }
        .section-title {
            margin-top: 24px;
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 12px;
        }
        .footer {
            background-color: #f3f4f6;
            text-align: center;
            padding: 16px;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Klinplus</h2>
            <p>Invoice Booking Order</p>
        </div>
        <div class="content">
            <p>Hai <strong>{{ $pelanggan->nama_pelanggan }}</strong>,</p>
            <p>Berikut adalah detail pesanan Anda:</p>

            <div class="section-title">Informasi Pelanggan</div>
            <div class="info-item"><span>Nama : </span><span>{{ $pelanggan->nama_pelanggan }}</span></div>
            <div class="info-item"><span>Email : </span><span>{{ $pelanggan->email }}</span></div>
            <div class="info-item"><span>WhatsApp : </span><span>{{ $pelanggan->telp_pelanggan }}</span></div>
            <div class="info-item"><span>Alamat : </span><span>{{ $order->alamat_lokasi ?? $pelanggan->alamat_lokasi ?? '-' }}</span></div>

            <div class="section-title">Detail Booking</div>
            <div class="info-item"><span>Tanggal Pengerjaan : </span><span>{{ \Carbon\Carbon::parse($order->tanggal_pengerjaan)->translatedFormat('d F Y') }}</span></div>
            <div class="info-item"><span>Jam : </span><span>{{ $order->jam_pengerjaan }}</span></div>

            <div class="section-title">Layanan</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailLayanan as $item)
                    <tr>
                        <td>{{ $item->nama_subkategori }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($order->diskon > 0)
                <div class="info-item"><span>Diskon : </span><span>- Rp {{ number_format($order->diskon, 0, ',', '.') }}</span></div>
            @endif

            <div class="total">
                Total: Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </div>
        </div>
        <div class="footer">
            Terima kasih telah menggunakan layanan kami.  
            <br>Jika ada pertanyaan, hubungi kami via WhatsApp.
        </div>
    </div>
</body>
</html>
