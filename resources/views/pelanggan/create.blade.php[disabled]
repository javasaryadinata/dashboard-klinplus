@extends('layouts.app')

@section('title-content')
<h1>Detail Pelanggan</h1>
@endsection

@section('content')
    <div class=""></div>
@endsection
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6">Tambah Pelanggan</h1>
    
    <form action="{{ route('pelanggan.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama_pelanggan" class="block text-gray-700">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" id="nama_pelanggan" 
                   class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label for="nomor_telepon" class="block text-gray-700">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" id="nomor_telepon" 
                   class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" 
                   class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <button type="" class="btn-back">Batal</button>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Simpan
        </button>
    </form>
</div>
@endsection