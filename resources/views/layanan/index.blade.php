@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Layanan</h3>
@endsection

@section('content')
<div x-data="{
        activeTab: '{{ request('active_tab', session('kategori_success') || session('kategori_error') ? 'kategori' : 'layanan') }}',

        isKategoriModalOpen: false,

        isLayananModalOpen: false,
        isEditLayanan: false,
        layananActionUrl: '{{ route('layanan.store') }}',
        layananFormData: {
            layanan_rootkategori_id: '',
            nama_subkategori: '',
            harga: ''
        },

        openAddLayanan() {
            this.isEditLayanan = false;
            this.layananActionUrl = '{{ route('layanan.store') }}';
            this.layananFormData = { layanan_rootkategori_id: '', nama_subkategori: '', harga: '' };
            this.isLayananModalOpen = true;
        },

        openEditLayanan(sub) {
            this.isEditLayanan = true;
            this.layananActionUrl = `/layanan/${sub.id}`;
            this.layananFormData = {
                layanan_rootkategori_id: sub.layanan_rootkategori_id,
                nama_subkategori: sub.nama_subkategori,
                harga: sub.harga
            };
            this.isLayananModalOpen = true;
        }
    }">

    <div class="border-b border-gray-200 mb-6 flex overflow-x-auto hide-scrollbar">
        <button @click="activeTab = 'layanan'"
                :class="{'border-cyan text-cyan font-bold': activeTab === 'layanan', 'border-transparent text-gray-500 hover:text-gray-700 font-medium': activeTab !== 'layanan'}"
                class="px-6 py-3 border-b-2 transition-colors whitespace-nowrap outline-none">
            Daftar Layanan
        </button>
        <button @click="activeTab = 'kategori'"
                :class="{'border-cyan text-cyan font-bold': activeTab === 'kategori', 'border-transparent text-gray-500 hover:text-gray-700 font-medium': activeTab !== 'kategori'}"
                class="px-6 py-3 border-b-2 transition-colors whitespace-nowrap outline-none">
            Kategori Layanan
        </button>
    </div>

    @if(session('layanan_success') || session('kategori_success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="global-alert">
            {{ session('layanan_success') ?? session('kategori_success') }}
        </div>
    @endif
    @if(session('layanan_error') || session('kategori_error') || session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm" id="global-error">
            {{ session('layanan_error') ?? session('kategori_error') ?? session('error') }}
        </div>
    @endif

    <div x-show="activeTab === 'layanan'" x-transition.opacity.duration.300ms style="display: none;">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
            <form action="{{ route('layanan.index') }}" method="GET" autocomplete="off" class="relative w-full max-w-sm">
                <input type="hidden" name="active_tab" value="layanan">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" placeholder="Cari" value="{{ $search }}"
                    class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
                @if($search)
                    <a href="{{ route('layanan.index') }}?active_tab=layanan" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </a>
                @endif
            </form>

            <button @click="openAddLayanan()" class="bg-cyan hover:bg-[#27b9d9] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap w-full md:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Layanan
            </button>
        </div>

        <div class="overflow-x-auto w-full mb-4">
            <div class="min-w-[800px]">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-semibold w-16">No</th>
                            <th class="px-4 py-3 font-semibold">Kategori Root</th>
                            <th class="px-4 py-3 font-semibold">Nama Layanan</th>
                            <th class="px-4 py-3 font-semibold">Harga Dasar</th>
                            <th class="px-4 py-3 font-semibold text-center w-28">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @php $i = 1; $hasResults = false; @endphp
                        @foreach($rootkategori as $root)
                            @foreach($root->subkategori as $sub)
                                @php $hasResults = true; @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors align-top">
                                    <td class="px-4 py-3">{{ $i++ }}</td>
                                    <td class="px-4 py-3"><span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-medium border border-gray-200">{{ $root->nama_rootkategori }}</span></td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $sub->nama_subkategori }}</td>
                                    <td class="px-4 py-3 font-semibold text-cyan">Rp {{ number_format($sub->harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center gap-1 justify-center">
                                            <button type="button" @click="openEditLayanan({{ json_encode($sub) }})" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition-colors" title="Edit Layanan">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <form action="{{ route('layanan.destroy', $sub->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if(!$hasResults)
                            <tr><td colspan="5" class="text-center py-8 text-gray-500">Layanan "{{ $search }}" tidak ditemukan.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'kategori'" x-transition.opacity.duration.300ms style="display: none;">

        <div class="flex justify-end mb-6">
            <button @click="isKategoriModalOpen = true" class="bg-cyan hover:bg-[#27b9d9] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap w-full md:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Kategori Root
            </button>
        </div>

        <div class="overflow-x-auto w-full mb-4">
            <div class="min-w-[600px] max-w-4xl">
                <table class="w-full text-left border-collapse border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold w-16">No</th>
                            <th class="px-6 py-3 font-semibold">Nama Kategori Root</th>
                            <th class="px-6 py-3 font-semibold text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700 bg-white">
                        @forelse($all_categories as $i => $root)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3">{{ $i+1 }}</td>
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $root->nama_rootkategori }}</td>
                            <td class="px-6 py-3 text-center">
                                <form action="{{ route('layanan.kategori.destroy', $root->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-red-500 hover:bg-red-50 rounded-md transition-colors text-xs font-semibold" onclick="return confirm('Yakin hapus kategori ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-6 text-gray-500">Belum ada kategori terdaftar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="isLayananModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isLayananModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isLayananModalOpen" @click.away="isLayananModalOpen = false" x-transition 
                class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg rounded-2xl">

                <form :action="layananActionUrl" method="POST">
                    @csrf
                    <template x-if="isEditLayanan">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-2xl">
                        <h5 class="font-bold text-lg text-gray-800" x-text="isEditLayanan ? 'Edit Layanan' : 'Tambah Layanan Baru'"></h5>
                        <button type="button" @click="isLayananModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 bg-gray-50/30">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Root <span class="text-red-500">*</span></label>
                            <select x-model="layananFormData.layanan_rootkategori_id" name="layanan_rootkategori_id" required
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                @foreach($all_categories as $r)
                                    <option value="{{ $r->id }}">{{ $r->nama_rootkategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sub-Layanan <span class="text-red-500">*</span></label>
                            <input type="text" x-model="layananFormData.nama_subkategori" name="nama_subkategori" required
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white" placeholder="Contoh: Cuci Karpet - Small">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Dasar (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" x-model="layananFormData.harga" name="harga" required min="0" step="1000"
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white" placeholder="Contoh: 150000">
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isLayananModalOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-cyan hover:bg-[#27b9d9] text-white rounded-xl font-medium transition-colors shadow-sm" x-text="isEditLayanan ? 'Simpan Perubahan' : 'Tambahkan'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="isKategoriModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isKategoriModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isKategoriModalOpen" @click.away="isKategoriModalOpen = false" x-transition 
                class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-sm rounded-2xl">

                <form method="POST" action="{{ route('layanan.kategori.store') }}">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-2xl">
                        <h5 class="font-bold text-lg text-gray-800">Tambah Kategori Root</h5>
                        <button type="button" @click="isKategoriModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 bg-gray-50/30">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_rootkategori" required
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white" placeholder="Contoh: Cuci Karpet">
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isKategoriModalOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-cyan hover:bg-[#27b9d9] text-white rounded-xl font-medium transition-colors shadow-sm">Simpan</button>
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
        const alerts = document.querySelectorAll('#global-alert, #global-error');
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