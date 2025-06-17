<template x-if="showBookingDetail">
    <div x-data="$root" class="fixed inset-0 bg-black/20 bg-opacity-50 flex items-center justify-center z-[100] p-2">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="p-4">
                <h2 class="text-xl font-bold text-center text-cyan">Booking Detail</h2>
            </div>

            <div class="overflow-y-auto p-6 flex-1">
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Informasi Pelanggan</h3>
                        <div class="grid grid-rows-5 gap-y-2">
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Nama Lengkap</span><span x-text="form.nama_lengkap"></span>
                            </div>
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Nomor Whatsapp</span><span x-text="form.whatsapp"></span>
                            </div>
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Alamat Lengkap</span><span class="w-64 text-right" x-text="form.alamat"></span>
                            </div>
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Kota</span><span x-text="form.nama_kota"></span>
                            </div>
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Catatan</span><span class="w-64 text-right" x-text="form.catatan || '-'"></span>
                            </div>
                        </div>
                </div>
    
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Tanggal & Waktu Layanan</h3>
                        <div class="grid grid-rows-2 gap-y-2">
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Tanggal</span><span x-text="formatTanggal(form.tanggal)"></span>
                            </div>
                            <div class="flex justify-between bodytext-dark-sm gap-1">
                                <span>Waktu</span><span x-text="form.waktu + ' WIB'"></span>
                            </div>
                        </div>
                </div>
    
                <div class="mb-4">
                    <h4 class="font-semibold mb-2">Estimasi Harga</h3>
                    <template x-for="layanan in selectedLayanan" :key="layanan.id">
                        <div class="flex justify-between mb-2">
                            <div class="grid grid-rows-2">
                                <span class="bodytext-dark-sm" x-text="layanan.nama_rootkategori"></span>
                                <span class="ml-4 text-xs bodytext-dark text-" x-text="layanan.nama_subkategori"></span>
                            </div>
                            <span x-text="'Rp. ' + Intl.NumberFormat('id-ID').format(layanan.harga)"></span>
                        </div>
                    </template>
                    <div x-show="form.diskon > 0" class="flex justify-between text-sm mt-2">
                        <span>Diskon</span>
                        <span x-text="'-Rp. ' + Intl.NumberFormat('id-ID').format(form.diskon)"></span>
                    </div>
                    <div class="border-t mt-2 pt-2 flex justify-between bodytext-dark-semibold">
                        <span>Total</span>
                        <span x-text="'Rp. ' + new Intl.NumberFormat('id-ID').format(totalHarga())"></span>
                    </div>
                </div>
            </div>


            <div class="flex justify-end space-x-2 mt-4">
                <button @click="showBookingDetail = false" class="px-4 py-2 text-sm rounded bg-gray-200 hover:bg-gray-300">Batal</button>
                <button type="button" @click="submitForm()" :disabled="isLoading" class="px-4 py-2 text-sm rounded bg-cyan text-white hover:bg-cyan-700">
                    <span x-show="!isLoading">Booking</span>
                    <span x-show="isLoading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>