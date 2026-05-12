@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Pelanggan</h3>
@endsection

@section('content')
<div x-data="{ 
        isModalOpen: false, 
        isEdit: false,
        actionUrl: '{{ route('pelanggan.store') }}',
        formData: {
            id_pelanggan: '',
            nama_pelanggan: '',
            telp_pelanggan: '',
            email: '',
            id_kota: '',
            alamat_lokasi: '',
            lokasi_gmaps: '',
            catatan: ''
        },

        openAddModal() {
            this.isEdit = false;
            this.actionUrl = '{{ route('pelanggan.store') }}';
            // Reset form
            this.formData = {
                id_pelanggan: '', nama_pelanggan: '', telp_pelanggan: '',
                email: '', id_kota: '', alamat_lokasi: '', lokasi_gmaps: '', catatan: ''
            };
            this.isModalOpen = true;
        },

        openEditModal(pelanggan) {
            this.isEdit = true;
            this.actionUrl = `/pelanggan/${pelanggan.id_pelanggan}`;
            // Isi form dengan data
            this.formData = {
                id_pelanggan: pelanggan.id_pelanggan,
                nama_pelanggan: pelanggan.nama_pelanggan || '',
                telp_pelanggan: pelanggan.telp_pelanggan || '',
                email: pelanggan.email || '',
                id_kota: pelanggan.id_kota || '',
                alamat_lokasi: pelanggan.alamat_lokasi || '',
                lokasi_gmaps: pelanggan.lokasi_gmaps || '',
                catatan: pelanggan.catatan || ''
            };
            this.isModalOpen = true;
        },

        formatPhone(e) {
            this.formData.telp_pelanggan = e.target.value.replace(/[^0-9]/g, '');
        }
    }">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">

        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-1">
            <form method="GET" action="{{ route('pelanggan.index') }}" autocomplete="off" class="relative w-full max-w-sm">
                @if(request('filter_kota'))
                    <input type="hidden" name="filter_kota" value="{{ request('filter_kota') }}">
                @endif

                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>

                <input type="text" name="search" placeholder="Cari" value="{{ request('search') }}"
                    class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">

                @if(request('search'))
                    <a href="{{ route('pelanggan.index', array_merge(request()->except('search'))) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </form>

            <form method="GET" action="{{ route('pelanggan.index') }}" class="flex items-center gap-2">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <label class="font-semibold text-gray-700 text-sm whitespace-nowrap hidden sm:block">Kota:</label>
                <select name="filter_kota" onchange="this.form.submit()"
                        class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 cursor-pointer shadow-sm w-full sm:w-auto">
                    <option value="">Semua</option>
                    @foreach($kotas as $kota)
                        <option value="{{ $kota->id_kota }}" {{ request('filter_kota') == $kota->id_kota ? 'selected' : '' }}>
                            {{ $kota->nama_kota }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <button type="button" @click="openAddModal()" class="bg-cyan hover:bg-[#27b9d9] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap w-full md:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pelanggan
        </button>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="pelanggan-alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm" id="pelanggan-error">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">
            <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto w-full mb-4">
        <div class="min-w-[1000px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold w-16">No</th>
                        <th class="px-4 py-3 font-semibold">ID Pelanggan</th>
                        <th class="px-4 py-3 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 font-semibold">No. Telepon</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Kota</th>
                        <th class="px-4 py-3 font-semibold w-64">Alamat</th>
                        <th class="px-4 py-3 font-semibold text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($pelanggans as $pelanggan)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $pelanggan->id_pelanggan ?? 'CS000000' }}</td>
                        <td class="px-4 py-3 font-semibold text-cyan">{{ $pelanggan->nama_pelanggan }}</td>
                        <td class="px-4 py-3">{{ $pelanggan->telp_pelanggan }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $pelanggan->email ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium border border-gray-200 shadow-sm">{{ $pelanggan->kota->nama_kota ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs leading-relaxed text-gray-500">{{ Str::limit($pelanggan->alamat_lokasi, 30) }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center gap-1 justify-center">
                                <button type="button" @click="openEditModal({{ json_encode($pelanggan) }})" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition-colors" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>

                                <form action="{{ route('pelanggan.destroy', $pelanggan->id_pelanggan) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">Tidak ada data pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($pelanggans->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $pelanggans->links() }}
        </div>
    @endif

    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isModalOpen" @click.away="isModalOpen = false" x-transition 
                class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-2xl rounded-2xl">

                <form :action="actionUrl" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-2xl">
                        <h5 class="font-bold text-lg text-gray-800" x-text="isEdit ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru'"></h5>
                        <button type="button" @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 bg-gray-50/30 max-h-[70vh] overflow-y-auto">
                        <div x-show="isEdit" class="mb-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">ID Pelanggan</label>
                            <input type="text" x-model="formData.id_pelanggan" class="w-full bg-gray-100 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed font-medium" readonly>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.nama_pelanggan" name="nama_pelanggan" required minlength="3"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.telp_pelanggan" name="telp_pelanggan" required minlength="9" maxlength="15" @input="formatPhone"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" x-model="formData.email" name="email"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kota <span class="text-red-500">*</span></label>
                                <select x-model="formData.id_kota" name="id_kota" required
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                                    <option value="" disabled>Pilih Kota</option>
                                    @foreach($kotas as $kota)
                                        <option value="{{ $kota->id_kota }}">{{ $kota->nama_kota }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea x-model="formData.alamat_lokasi" name="alamat_lokasi" rows="2" required
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Google Maps</label>
                            <input type="url" x-model="formData.lokasi_gmaps" name="lokasi_gmaps" placeholder="https://maps.google.com/..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                            <textarea x-model="formData.catatan" name="catatan" rows="2" placeholder="Contoh: Pagar hitam"
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white"></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-cyan hover:bg-[#27b9d9] text-white rounded-xl font-medium transition-colors shadow-sm" x-text="isEdit ? 'Simpan Perubahan' : 'Tambahkan'"></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {  
        const alerts = document.querySelectorAll('#pelanggan-alert, #pelanggan-error');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 3000); 
        });
    });
</script>
@endpush


