@extends('layouts.app')

@section('title-content')
<h1>Riwayat</h1>
@endsection

@section('content')
<div class="container-table">
    <div class="table-wrapper">
        <table class="staf-table">
            <thread>
                <tr>
                    <th>#</th>
                    <th>Id Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Layanan</th>
                    <th>Alamat</th>
                    <th>Tanggal Pembersihan</th>
                    <th>Waktu Pembersihan</th>
                    <th>Durasi</th>
                    <th>Nama Petugas</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thread>
        </table>
    </div>
</div>
@endsection
