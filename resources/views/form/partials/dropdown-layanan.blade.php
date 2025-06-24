<div class="relative inline-block gap-y-4">
    <button type="button" @click="showDropdown = !showDropdown" class="flex items-center space-x-1 h-8 border border-cyan text-cyan text-sm px-4 rounded-lg hover:bg-sky-50">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tambah layanan</span>
    </button>
    <div x-show="showDropdown" @keydown.escape="HandleClearSearch($event)" x-transition @click.away="showDropdown = false" class="absolute left-0 md:left-full mt-2 md:ml-2 md:top-0 w-64 md:w-98 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-96 overflow-y-auto">
        <div class="sticky top-0 bg-white p-2 z-20">
            <div class="relative">
                <input type="text" x-model="keyword" placeholder="Cari..." class="w-full border px-2 py-1 rounded text-sm focus:outline-none focus:border-cyan" @keydown.enter.prevent @keydown.escape="handleClearSearch($event)">
                <button type="button" @click.stop="handleClearSearch($event)" x-show="keyword" class="absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-400 rounded-sm hover:bg-gray-200 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>                                                  
                </button>
            </div>
        </div>
        <div class="overflow-y-auto max-h-[calc(100%-40px)]">
            <template x-for="(root, rootIndex) in filteredOptions()" :key="root.id">
                <div class="mb-2">
                    <div x-show="rootIndex > 0" class="border-t border-gray-400 my-2"></div>
                    <p class="font-semibold text-sm text-gray-700 mb-1" x-text="root.nama_rootkategori"></p>
                    <template x-for="(sub, subIndex) in root.subkategori" :key="sub.id">
                        <div>
                            <div x-show="subIndex > 0" class="border-t border-gray-200 my-1 mx2"></div>
                            <div @click="addLayanan(sub.id, sub)"
                            class="cursor-pointer px-2 py-1 hover:bg-sky-50 rounded text-sm">
                            <span x-text="sub.nama_subkategori"></span> -
                            <span x-text="'Rp. ' + Intl.NumberFormat('id-ID').format(sub.harga)"></span>
                        </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>