<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Booking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            background-color: #eeeeee;
            color: #333;
        }
        a {
            color: #47BFEC;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.127); */
        }
        .header {
            background-color: #47BFEC;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header-title {
            margin: 0px;
        }
        .content {
            padding: 24px;
        }
        p {
            margin-block-start: 0px;
            margin-block-end: 0px;
        }
        .nama-pelanggan {
            margin-block-end: 1em;
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
        .layanan {
            margin-inline-start: 14px;
        }
        .diskon {
            text-align: right;
            margin-top: 8px;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 8px;
        }
        .note {
            margin-top: 20px;
        }
        .footer {
            background-color: #47c0ec1e;
            text-align: center;
            padding: 30px;
            font-size: 14px;
            color: #333;
        }
        .copyright {
            font-size: 12px;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 class="header-title">KLINPLUS</h2>
            <p class="header-title">Informasi Booking</p>
        </div>
        <div class="content">
            <p class="nama-pelanggan">Halo <strong>{{ $order->pelanggan->nama_pelanggan }}</strong>,</p>
            <p>
                Terimakasih telah menggunakan layanan Klinplus!<br>
                Anda baru saja mengajukan booking dengan detail sebagai berikut:
            </p>

            <div class="section-title">Informasi Pelanggan</div>
            <div class="info-item"><span>Nama : <strong>{{ $order->pelanggan->nama_pelanggan }}</strong></span></div>
            <div class="info-item"><span>Email : <strong>{{ $order->pelanggan->email }}</strong></span></div>
            <div class="info-item"><span>WhatsApp : <strong>{{ $order->pelanggan->telp_pelanggan }}</strong></span></div>
            <div class="info-item"><span>Alamat : <strong>{{ $order->alamat_lokasi ?? $pelanggan->alamat_lokasi ?? '-' }}</strong></span></div>

            <div class="section-title">Detail Booking</div>
            <div class="info-item"><span>Tanggal Pengerjaan : <strong>{{ \Carbon\Carbon::parse($order->tanggal_pengerjaan)->translatedFormat('d F Y') }}</strong></span></div>
            <div class="info-item"><span>Waktu Pengerjaan : <strong>{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->translatedFormat('H:i') . ' WIB' }}</strong></span></div>

            <div class="section-title">Layanan</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $item)
                    <tr>
                        <td>
                            <span>{{ $item->layananSubkategori->rootKategori->nama_rootkategori ?? '-' }}</span><br>
                            <span class="layanan">{{ $item->layananSubkategori->nama_subkategori ?? '-' }}</span>
                        </td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($order->diskon > 0)
                <div class="diskon"><span>Diskon : </span><span>- Rp {{ number_format($order->diskon, 0, ',', '.') }}</span></div>
            @endif

            <div class="total">
                Total : Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </div>

            <p class="note">
                Kami akan menghubungi anda melalui whatsapp untuk melakukan pembayaran DP dan memproses booking anda. Pastikan nomor whatsapp anda sudah benar, apabila nomor whatsapp anda salah bisa hubungi kami melalui whatsapp atau klik link
                <a href="https://wa.me/6281331155778">klinplus.id/bantuan</a>
            </p>
            <p class="note">Terimakasih,</p>
            <p>Tim Klinplus</p>
        </div>
        <div class="footer"> 
            <span>
                Ada pertanyaan? hubungi kami melalui WhatsApp<br>
                atau klik link
                <a href="https://wa.me/6281331155778">klinplus.id/bantuan</a>
            </span>
            <p class="copyright">Copyright © 2025 PT. Cakra Sinergi Inovasi. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
