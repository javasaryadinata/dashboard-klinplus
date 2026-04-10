<div class="relative inline-block gap-y-4">
    <button type="button" @click="showDropdown = !showDropdown" class="flex items-center space-x-1 h-8 border border-cyan text-cyan text-sm px-4 rounded-lg hover:bg-sky-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tambah layanan</span>
    </button>
    <div x-show="showDropdown" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         role="dialog" aria-modal="true">
        
        <div x-show="showDropdown" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showDropdown = false" 
             class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>

        <div x-show="showDropdown"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
            
            <div class="p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Pilih Layanan Klinplus</h3>
                    <button @click="showDropdown = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input type="text" 
                           x-model="keyword" 
                           placeholder="Cari layanan apa hari ini?" 
                           class="w-full border-gray-300 border pl-10 pr-10 py-2.5 rounded-xl text-sm focus:ring-2 focus:ring-cyan/20 focus:border-cyan outline-none transition-all shadow-sm" 
                           @keydown.escape="handleClearSearch($event)">

                    <button type="button" 
                            x-show="keyword.length > 0" 
                            @click="keyword = ''" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-cyan focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 overflow-y-auto flex-1 bg-gray-50/50">
                <template x-for="(root, rootIndex) in filteredOptions()" :key="root.id">
                    <div class="mb-6 last:mb-0">
                        <p class="font-bold text-base text-cyan uppercase tracking-widest mb-2" x-text="root.nama_rootkategori"></p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="(sub, subIndex) in root.subkategori" :key="sub.id">
                                <div @click="addLayanan(sub.id, sub); showDropdown = false"
                                     class="cursor-pointer p-4 bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-cyan transition-all rounded-xl flex flex-col items-start min-h-[80px]">
                                    <span class="bodytext-dark mb-auto" x-text="sub.nama_subkategori"></span>
                                    <span class="bodytext-dark-sm mt-auto" x-text="'Rp ' + Intl.NumberFormat('id-ID').format(sub.harga)"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>