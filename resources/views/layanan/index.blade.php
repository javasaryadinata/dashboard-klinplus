@extends('layouts.app')

@section('title-content')
    <h1>Layanan</h1>
@endsection

@section('content')
<ul class="nav nav-tabs mb-3" id="layananTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="kategori-tab" data-bs-toggle="tab" data-bs-target="#kategori" type="button" role="tab">Kategori</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="layanan-tab" data-bs-toggle="tab" data-bs-target="#layanan" type="button" role="tab">Layanan</button>
  </li>
</ul>
<div class="tab-content" id="layananTabContent">
  <!-- Tab Kategori -->
  <div class="tab-pane fade show active" id="kategori" role="tabpanel">
    @include('layanan._kategori', ['rootkategori' => $rootkategori])
  </div>
  <!-- Tab Layanan -->
  <div class="tab-pane fade" id="layanan" role="tabpanel">
    @include('layanan._layanan', ['rootkategori' => $rootkategori])
  </div>
</div>
@endsection
@push('scripts')
<script>
    // Default: tab kategori aktif
    @if(session('layanan_success') || session('layanan_error'))
        // Jika ada notifikasi layanan, aktifkan tab layanan
        var layananTab = new bootstrap.Tab(document.getElementById('layanan-tab'));
        layananTab.show();
    @elseif(session('kategori_success') || session('kategori_error'))
        // Jika ada notifikasi kategori, aktifkan tab kategori
        var kategoriTab = new bootstrap.Tab(document.getElementById('kategori-tab'));
        kategoriTab.show();
    @endif
</script>
@endpush