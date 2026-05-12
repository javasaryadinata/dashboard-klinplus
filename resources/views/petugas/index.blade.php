@extends('layouts.app')

@section('title-content')
<h3 class="font-semibold text-3xl 2xl:text-4xl">Petugas</h3>
@endsection

@section('content')
<div x-data="{
        isModalOpen: false, 
        isEdit: false,
        actionUrl: '{{ route('petugas.store') }}',
        formData: {
            id_petugas: '',
            nama_petugas: '',
            no_telp: ''
        },

        openAddModal() {
            this.isEdit = false;
            this.actionUrl = '{{ route('petugas.store') }}';
            this.formData = { id_petugas: '', nama_petugas: '', no_telp: '' };
            this.isModalOpen = true;
        },

        openEditModal(petugas) {
            this.isEdit = true;
            this.actionUrl = `/petugas/${petugas.id_petugas}`;
            this.formData = {
                id_petugas: petugas.id_petugas,
                nama_petugas: petugas.nama_petugas,
                no_telp: petugas.no_telp
            };
            this.isModalOpen = true;
        },

        formatPhone(e) {
            this.formData.no_telp = e.target.value.replace(/[^0-9]/g, '');
        }
    }">

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">

        <form method="GET" action="{{ route('petugas.index') }}" autocomplete="off" class="relative w-full max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <input type="text" name="search" placeholder="Cari" value="{{ request('search') }}"
                class="w-full bg-white border border-gray-300 pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm">
            
            @if(request('search'))
                <a href="{{ route('petugas.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>

        <button type="button" @click="openAddModal()" class="bg-cyan hover:bg-[#27b9d9] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap w-full md:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Petugas
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm" id="petugas-alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm" id="petugas-error">
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
        <div class="min-w-[800px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold w-16">No</th>
                        <th class="px-4 py-3 font-semibold">ID Petugas</th>
                        <th class="px-4 py-3 font-semibold">Nama Petugas</th>
                        <th class="px-4 py-3 font-semibold">Nomor Telepon</th>
                        <th class="px-4 py-3 font-semibold text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($petugas as $ptg)
                    <tr class="hover:bg-gray-50/50 transition-colors align-top">
                        <td class="px-4 py-3">{{ $loop->iteration + ($petugas->currentPage() - 1) * $petugas->perPage() }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $ptg->id_petugas }}</td>
                        <td class="px-4 py-3 font-semibold text-cyan">{{ $ptg->nama_petugas }}</td>
                        <td class="px-4 py-3">{{ $ptg->no_telp }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center gap-1 justify-center">
                                <button type="button" @click="openEditModal({{ json_encode($ptg) }})" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-md transition-colors" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>

                                <form action="{{ route('petugas.destroy', $ptg->id_petugas) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">Tidak ada data petugas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($petugas->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $petugas->links() }}
        </div>
    @endif

    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-600 bg-opacity-50 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isModalOpen" @click.away="isModalOpen = false" x-transition
                class="relative transform bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg rounded-2xl">
                
                <form :action="actionUrl" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-2xl">
                        <h5 class="font-bold text-lg text-gray-800" x-text="isEdit ? 'Edit Petugas' : 'Tambah Petugas Baru'"></h5>
                        <button type="button" @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-transparent border-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 bg-gray-50/30">
                        <div x-show="isEdit" class="mb-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ID Petugas</label>
                            <input type="text" x-model="formData.id_petugas" class="w-full bg-gray-100 border border-gray-200 text-gray-500 px-4 py-2.5 rounded-xl text-sm outline-none cursor-not-allowed font-medium" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Petugas <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.nama_petugas" name="nama_petugas" required minlength="3"
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white" placeholder="Masukkan nama petugas...">
                        </div> 

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" x-model="formData.no_telp" name="no_telp" required minlength="10" maxlength="15" @input="formatPhone"
                                   class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm outline-none focus:border-cyan focus:ring-2 focus:ring-cyan/20 bg-white" placeholder="Contoh: 081234567890">
                            <p class="text-xs text-gray-400 mt-1">081234567890</p>
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
        const alerts = document.querySelectorAll('#petugas-alert, #petugas-error');
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
