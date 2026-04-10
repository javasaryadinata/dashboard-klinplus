@extends('layouts.app')

@section('title-content')
    <h1>Layanan</h1>
@endsection

@section('content')
@php $activeTab = request('active_tab', 'kategori'); @endphp
<ul class="nav nav-tabs mb-3" id="layananTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link {{ $activeTab == 'kategori' ? 'active' : '' }}" id="kategori-tab" data-bs-toggle="tab" data-bs-target="#kategori" type="button" role="tab">Kategori</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link {{ $activeTab == 'layanan' ? 'active' : '' }}" id="layanan-tab" data-bs-toggle="tab" data-bs-target="#layanan" type="button" role="tab">Layanan</button>
  </li>
</ul>
<div class="tab-content" id="layananTabContent">
  <div class="tab-pane fade {{ $activeTab == 'kategori' ? 'show active' : '' }}" id="kategori" role="tabpanel">
    @include('layanan._kategori', ['rootkategori' => $all_categories])
  </div>

  <div class="tab-pane fade {{ $activeTab == 'layanan' ? 'show active' : '' }}" id="layanan" role="tabpanel">
    @include('layanan._layanan', ['rootkategori' => $rootkategori])
  </div>
</div>
@endsection
@push('scripts')
<script>
    // Logika pindah tab otomatis
    @if(request('active_tab') == 'layanan' || session('layanan_success') || session('layanan_error'))
        // Aktifkan tab layanan jika sedang search layanan atau ada notifikasi layanan
        var layananTab = new bootstrap.Tab(document.getElementById('layanan-tab'));
        layananTab.show();
    @elseif(request('active_tab') == 'kategori' || session('kategori_success') || session('kategori_error'))
        // Aktifkan tab kategori jika sedang di tab kategori atau ada notifikasi kategori
        var kategoriTab = new bootstrap.Tab(document.getElementById('kategori-tab'));
        kategoriTab.show();
    @endif
</script>
@endpush