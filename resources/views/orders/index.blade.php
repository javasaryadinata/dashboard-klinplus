@extends('layouts.app')

@section('title-content')
<h1>Order</h1>
@endsection

@section('content')
<div class="container">
    <div class="btn btn-primary">
        <button class="btn btn-new" data-bs-toggle="modal" data-bs-target="#tambahOrderModal">
            Tambah Order Baru
        </button>
    </div>
</div>
@if(session('success'))
<div class="alert alert-success" id="order-success-alert">{{ session('success') }}</div>
@endif
<div class="container-table">
    <div class="table-wrapper">
        <table class="order-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Order</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Pengerjaan</th>
                    <th>Jam Pengerjaan</th>
                    <th>Alamat</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Status Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $totalDurasi = $order->orderDetails->sum('durasi_layanan');
                    $jamMulai = \Carbon\Carbon::createFromFormat('H:i:s', $order->jam_pengerjaan);
                    $jamSelesai = $totalDurasi ? $jamMulai->copy()->addMinutes($totalDurasi)->format('H:i') . ' WIB' : '-';
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->id_order }}</td>
                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $order->tanggal_pengerjaan ? \Carbon\Carbon::parse($order->tanggal_pengerjaan)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $order->jam_pengerjaan ? \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') : '-' }}</td>
                    <td>{{ $order->alamat_lokasi ?? '-' }}</td>
                    <td>
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge px-2 py-1"
                            style="background:{{ $order->status === 'Request' ? '#FFC107' : ($order->status === 'Scheduled' ? '#B0DB9C' : ($order->status === 'Selesai' ? '#3FD6CB' : '#ddd')) }};">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        @php
                            $metode = $order->metode_pembayaran ? ucfirst($order->metode_pembayaran) : '-';
                            $tipe = $order->tipe_pembayaran ? ucfirst($order->tipe_pembayaran) : '-';
                        @endphp
                        {{ $metode }} / {{ $tipe }}
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('orders.detail', $order->id_order) }}" class="btn btn-info table-action-button">
                                Detail
                            </a>
                            <a href="{{ route('orders.invoicePdf', $order->id_order) }}" class="btn btn-info table-action-button" target="_blank">
                                Download Invoice
                            </a>
                            @if($order->pelanggan && $order->pelanggan->telp_pelanggan)
                                @php
                                    // Format: 62xxxxxxxxxx tanpa +/spasi/tanda lain
                                    $waNumber = preg_replace('/[^0-9]/', '', $order->pelanggan->telp_pelanggan);
                                    if (substr($waNumber, 0, 1) == '0') {
                                        $waNumber = '62' . substr($waNumber, 1); // ubah 08xxx jadi 628xxx
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}"
                                target="_blank"
                                class="btn btn-info table-action-button">
                                    WhatsApp
                                </a>
                            @else
                                <span class="text-muted">Tidak ada WA</span>
                            @endif
                            <form action="{{ route('orders.approve', $order->id_order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-approve table-action-button">
                                    Setuju
                                </button>
                            </form>
                            <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan order ini?')">
                                    Cancel
                                </button>
                            </form>
                            <form action="{{ route('orders.destroy', $order->id_order) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus order ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-hapus table-action-button">
                                    Hapus
                                </button>
                            </form>
                            {{-- <form action="{{ route('jadwal.reschedule', $order->id_order) }}" method="GET">
                                <button type="submit" class="btn btn-reschedule table-action-button">
                                    Re-schedule
                                </button>
                            </form> --}}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data order</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Tambah Order Modal -->
<div class="modal fade" id="tambahOrderModal" tabindex="-1" aria-labelledby="tambahOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-white text-dark">
              <h5 class="modal-title" id="tambahOrderModalLabel">Tambah Order Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="formTambahOrder" method="POST" action="{{ route('orders.store') }}">
              @csrf
              <div class="modal-body">
                <div class="mb-3">
                    <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                    <select class="form-select" id="id_pelanggan" name="id_pelanggan" required>
                        <option value="" selected disabled>Pilih Pelanggan</option>
                        @foreach($pelanggans as $pelanggan)
                        <option 
                            value="{{ $pelanggan->id_pelanggan }}"
                            data-alamat="{{ $pelanggan->alamat_lokasi }}"
                            data-gmaps="{{ $pelanggan->lokasi_gmaps ?? '' }}">
                            {{ $pelanggan->nama_pelanggan }} - {{ $pelanggan->alamat_lokasi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="input_alamat_lokasi" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="input_alamat_lokasi" name="alamat_lokasi" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="input_lokasi_gmaps" class="form-label">Gmaps</label>
                    <input type="text" class="form-control" id="input_lokasi_gmaps" name="lokasi_gmaps" readonly>
                </div>

                <div class="mb-3">
                    <label for="input_catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="input_catatan" name="catatan" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Layanan</label>
                    <div id="layanan-container">
                        <div class="row layanan-row mb-2">
                            <div class="col-7">
                                <select name="layanan_subkategori[]" class="form-select layanan-select" required>
                                    <option value="" disabled selected>Pilih Layanan</option>
                                    @foreach(\App\Models\LayananSubkategori::with('rootKategori')->get() as $layanan)
                                        <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}">
                                            {{ $layanan->rootKategori->nama_rootkategori ?? '' }} - {{ $layanan->nama_subkategori }} (Rp{{ number_format($layanan->harga,0,',','.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger btn-remove-layanan" style="display:none;">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-add-layanan mt-2">Tambah Layanan</button>
                </div>

                <div class="mb-3">
                    <label for="input_kode" class="form-label">Kode Diskon</label>
                    <input type="text" class="form-control" id="input_kode" name="kode" maxlength="20">
                    <div class="mt-2" id="diskon-msg"></div>
                </div>

                <div class="mb-3">
                    <label for="input_total_harga" class="form-label">Total Harga</label>
                    <input type="number" class="form-control" id="input_total_harga" name="total_harga" readonly required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="input_tanggal_pengerjaan" class="form-label">Tanggal Pengerjaan</label>
                        <input type="date" class="form-control" id="input_tanggal_pengerjaan" name="tanggal_pengerjaan" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="input_jam_pengerjaan" class="form-label">Waktu Pengerjaan</label>
                        <input type="time" class="form-control" id="input_jam_pengerjaan" name="jam_pengerjaan" required>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn-back" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn-add">Tambahkan</button>
              </div>
          </form>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('order-success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }, 3000); // 3 detik
    }

    const pelangganSelect = document.getElementById('id_pelanggan');
    const alamatInput = document.getElementById('input_alamat_lokasi');
    const gmapsInput = document.getElementById('input_lokasi_gmaps');

    function isiAlamatOtomatis() {
        const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
        if (selectedOption.value) {
            alamatInput.value = selectedOption.getAttribute('data-alamat') || '';
            gmapsInput.value = selectedOption.getAttribute('data-gmaps') || '';
        }
    }

    pelangganSelect.addEventListener('change', isiAlamatOtomatis);

    const orderModal = document.getElementById('tambahOrderModal');
    if (orderModal) {
        orderModal.addEventListener('shown.bs.modal', function() {
            isiAlamatOtomatis();
        });
    }

    const layananContainer = document.getElementById('layanan-container');
    const btnAddLayanan = document.querySelector('.btn-add-layanan');

    function updateRemoveButtons() {
        const rows = layananContainer.querySelectorAll('.layanan-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.btn-remove-layanan');
            btn.style.display = rows.length > 1 ? '' : 'none';
        });
    }
    
    // Tambah layanan dinamis
    btnAddLayanan.addEventListener('click', function() {
        const row = layananContainer.querySelector('.layanan-row');
        const clone = row.cloneNode(true);
        clone.querySelector('.layanan-select').selectedIndex = 0;
        layananContainer.appendChild(clone);
        setHargaOtomatis(clone);
        updateRemoveButtons();
        hitungTotalHarga();
    });

    layananContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-layanan')) {
            e.target.closest('.layanan-row').remove();
            updateRemoveButtons();
            hitungTotalHarga();
        }
    });

    layananContainer.addEventListener('input', function(e) {
        if (e.target.name === 'harga-layanan-input') {
            hitungTotalHarga();
        }
    });

    // Fungsi untuk mengisi jam_pembersihan otomatis 1 jam sebelum waktu_pembersihan
    function setJamPembersihanSebelumnya() {
        const waktuInput = document.getElementById('input_jam_pengerjaan');
        const jamSebelumnyaInput = document.getElementById('jam_pengerjaan_sebelumnya');

        waktuInput.addEventListener('input', function () {
            if (!waktuInput.value) {
                jamSebelumnyaInput.value = '';
                return;
            }

            const [hours, minutes] = waktuInput.value.split(':').map(Number);
            const date = new Date();
            date.setHours(hours);
            date.setMinutes(minutes);
            date.setMinutes(date.getMinutes() - 60); // Kurangi 1 jam

            const jam = String(date.getHours()).padStart(2, '0');
            const menit = String(date.getMinutes()).padStart(2, '0');

            jamSebelumnyaInput.value = `${jam}:${menit}`;
        });
    };

    // Data promo dari backend
    const diskonList = {
        {!! $promos->map(function($promo) {
            return '"'.strtoupper($promo->kode).'": '.(int)$promo->diskon;
        })->join(',') !!}
    };

    const kodeDiskonInput = document.getElementById('input_kode');
    const totalHargaInput = document.getElementById('input_total_harga');
    let diskonAktif = 0;

    // Tampilkan pesan validasi diskon
    let diskonMsg = document.getElementById('diskon-msg');
    if (!diskonMsg) {
        diskonMsg = document.createElement('div');
        diskonMsg.id = 'diskon-msg';
        diskonMsg.style.fontSize = '0.9em';
        kodeDiskonInput.parentNode.appendChild(diskonMsg);
    }

    function hitungTotalHarga() {
        let total = 0;
        layananContainer.querySelectorAll('.layanan-select').forEach(select => {
            const harga = select.options[select.selectedIndex]?.getAttribute('data-harga');
            total += parseInt(harga) || 0;
        });

        // Kurangi diskon jika ada
        let totalSetelahDiskon = total;
        if (diskonAktif > 0) {
            totalSetelahDiskon = Math.max(0, total - diskonAktif);
        }
        totalHargaInput.value = totalSetelahDiskon;
    }

    // Validasi kode diskon saat input
    kodeDiskonInput.addEventListener('input', function() {
        const kode = kodeDiskonInput.value.trim().toUpperCase();
        if (kode && diskonList[kode]) {
            diskonAktif = diskonList[kode];
            diskonMsg.textContent = `Kode valid : -Rp${diskonAktif.toLocaleString('id-ID')}`;
            diskonMsg.style.color = 'green';
        } else if (kode) {
            diskonAktif = 0;
            diskonMsg.textContent = 'Kode tidak valid';
            diskonMsg.style.color = 'red';
        } else {
            diskonAktif = 0;
            diskonMsg.textContent = '';
        }
        hitungTotalHarga();
    });

    // Harga otomatis dari pilihan layanan
    function setHargaOtomatis(row) {
        const select = row.querySelector('.layanan-select');
        select.addEventListener('change', function() {
            hitungTotalHarga();
        });
    }

    // Inisialisasi untuk baris pertama
    document.querySelectorAll('.layanan-row').forEach(row => setHargaOtomatis(row));
    updateRemoveButtons();
    hitungTotalHarga();
});
</script>
@endpush
