@extends('layouts.app')

@section('title-content')
<h1>Pembayaran</h1>
@endsection

@section('content')
<div class="container">
    <div class="btn-petugas">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPembayaranModal">
            Tambah Pembayaran Baru
        </a>
    </div>
</div>
<div class="container-table">
    <div class="table-wrapper">
        <table class="staf-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Id Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Layanan</th>
                    <th>Tanggal Pembersihan</th>
                    <th>Diskon</th>
                    <th>Total Harga</th>
                    <th>Status Pembayaran</th>
                    <th>Tipe Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->id_order }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan }}</td>
                    <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                    <td>
                        @php
                            $layananList = $order->orderDetails->map(function($detail) {
                                $root = $detail->layananSubkategori->rootKategori->nama_rootkategori ?? '';
                                $sub = $detail->layananSubkategori->nama_subkategori ?? '';
                                return trim($root . ' - ' . $sub, ' -');
                            })->unique()->implode(', ');
                        @endphp
                        {{ $layananList ?: '-' }}
                    </td>
                    <td>{{ $order->tanggal_pengerjaan }}</td>
                    <td>
                        {{-- Diskon, jika ada field diskon di order --}}
                        {{ $order->diskon ? 'Rp ' . number_format($order->diskon, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        @php
                            $totalHarga = $order->orderDetails->sum('harga');
                        @endphp
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </td>
                    <td>{{ $order->metode_pembayaran ?? '-' }}</td>
                    <td>{{ $order->tipe_pembayaran ?? '-' }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id_order) }}" class="btn btn-info btn-sm mb-1">
                            Detail
                        </a>
                        <a href="{{ route('pembayaran.invoice', $order->id_order) }}" class="btn btn-secondary btn-sm mb-1">
                            Invoice
                        </a>
                        <form action="{{ route('pembayaran.close', $order->id_order) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tutup pembayaran untuk order ini?')">
                                Tutup
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tambah Pembayaran Modal -->
<div class="modal fade" id="tambahPembayaranModal" tabindex="-1" aria-labelledby="tambahPembayaranModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-white text-dark">
                <h5 class="modal-title" id="tambahPembayaranModalLabel">Tambah Pembayaran Baru</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahPembayaran" method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="order_id" class="form-label">ID Order</label>
                            <select class="form-select" id="order_id" name="order_id" required>
                                <option value="" selected disabled>Pilih ID Order</option>
                                
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" readonly>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="layanan" class="form-label">Layanan</label>
                            <input type="text" class="form-control" id="layanan" name="layanan" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="diskon" class="form-label">Diskon (Rp)</label>
                            <input type="number" class="form-control" id="diskon" name="diskon" min="0" value="0">
                        </div>
                        <div class="col-md-6">
                            <label for="total_bayar" class="form-label">Total Bayar</label>
                            <input type="text" class="form-control" id="total_bayar" name="total_bayar" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="" selected disabled>Pilih Metode</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                            <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Set default date to today
    $('#tanggal_pembayaran').val(new Date().toISOString().substr(0, 10));

    // When order selection changes
    $('#order_id').change(function() {
        const orderId = $(this).val();
        if (orderId) {
            // Fetch order details via AJAX
            $.get(`/orders/${orderId}`, function(data) {
                $('#nama_pelanggan').val(data.pelanggan.nama);
                $('#layanan').val(data.layanan.nama);
                $('#total_harga').val(formatRupiah(data.total_harga));
                calculateTotalBayar();
            }).fail(function() {
                alert('Gagal memuat data order');
            });
        }
    });

    // When diskon changes
    $('#diskon').on('input', calculateTotalBayar);

    // Calculate total bayar
    function calculateTotalBayar() {
        const totalHarga = parseFloat($('#total_harga').val().replace(/\D/g,'')) || 0;
        const diskon = parseFloat($('#diskon').val()) || 0;
        const totalBayar = totalHarga - diskon;
        $('#total_bayar').val(formatRupiah(totalBayar));
    }

    // Format to Rupiah
    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }
});
</script>
@endpush

