<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->id_order }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 16px;
            padding: 40px;
        }
        .header {
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 20px 0 20px 0
        }
        .contact {
            text-align: right;
        }
        p {
            margin: 4px 0 0 0; 
        }
        .status {
            color: green;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 7px;
        }
        th {
            background: #f2f2f2;
        }
        .text-end {
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LOGO</h2>
        <p class="contact">
            Jl. Ngagel Jaya Utara No.152, Baratajaya<br>
            Kec. Gubeng, Surabaya, Jawa Timur 60284
        </p>
        <p class="contact">+6281331155778</p>
        <h2>INVOICE</h2>
        <p>ID Order : <b>{{ $order->id_order }}</b></p>
        <p>Pelanggan : {{ $order->pelanggan->nama_pelanggan }}</p>
        <p>Alamat : {{ $order->alamat_lokasi }}</p>
        <p>Tanggal Pengerjaan : {{ \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') }}</p>
        <p>Status : <b><span>{{ $order->metode_pembayaran }}</b></span></p>
    </div>
    <div>
        <p><b>Detail Layanan :</b></p>
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
    <div class="footer">
        <p style="margin-top: 20px;">Keterangan :</p>
        <p>1. Mohon melakukan pembayaran uang muka/DP sebesar 50% dari total tagihan, guna memproses booking anda. Bukti pembayaran dapat dikirimkan melalui whatsapp, dan pelunasan akan dilakukan sesuai kesepakatan.</p>
        <p>2. Reschedule/ganti tanggal pengerjaan dapat dilakukan dengan ketentuan maksimal H-1 sebelum tanggal pengerjaan awal.</p>
        <p style="margin-top: 20px;">Pembayaran dapat ditransfer ke rekening berikut :</p>
        <p style="margin-top: 10px;"><b>BCA</b></p>
        <p>No. Rekening : <span><b>12345678</b></span></p>
        <p>Atas Nama : <span><b>John Doe</b></span></p>
        <p style="margin-top: 10px;">
            Mohon konfirmasi setelah melakukan transfer melalui whatsapp, disertai dengan bukti pembayaran.
        </p>
        <p style="margin-top: 30px;"><i>Terima kasih telah menggunakan layanan Klinplus.</i></p>    
    </div>
</body>
</html>
