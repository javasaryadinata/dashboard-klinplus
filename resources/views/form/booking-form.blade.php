<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Klinplus | Booking Form</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="//unpkg.com/alpinejs" defer></script>
        <style>[x-cloak] { display: none !important; }</style>
    </head>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6281331155778" title="Chat Admin di WhatsApp" class="fixed z-50 bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg p-4 flex items-center justify-center transition duration-300" target="_blank" aria-label="Chat via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.52 3.48A11.85 11.85 0 0012 0C5.37 0 0 5.37 0 12a11.9 11.9 0 001.7 6.14L0 24l6.14-1.7A11.9 11.9 0 0012 24c6.63 0 12-5.37 12-12a11.85 11.85 0 00-3.48-8.52zM12 22a9.94 9.94 0 01-5.1-1.42l-.37-.22-3.64.98.97-3.53-.24-.37A9.93 9.93 0 1122 12a10 10 0 01-10 10zm5.64-7.29c-.29-.15-1.7-.84-1.96-.93s-.45-.15-.64.15-.74.93-.91 1.12-.34.22-.63.07a8.08 8.08 0 01-2.37-1.46 8.4 8.4 0 01-1.56-1.94c-.16-.28-.02-.43.12-.58s.29-.34.43-.52a1.94 1.94 0 00.29-.49.53.53 0 00-.02-.52c-.07-.14-.63-1.52-.87-2.09-.23-.56-.47-.49-.64-.5h-.54a1 1 0 00-.7.34A2.94 2.94 0 006 9.73a5.18 5.18 0 001.1 3.1 11.44 11.44 0 004.36 3.73 11.35 11.35 0 002.32.68c.98.1 1.87.06 2.58-.12a1.76 1.76 0 001.17-.81 2.17 2.17 0 00.16-1.01c-.06-.12-.25-.2-.54-.34z"/>
        </svg>
    </a>

    {{-- Body Form --}}
    <body x-data="BookingForm()" :class="{ 'overflow-hidden': showBookingDetail || showSuccessModal }" class="flex justify-center bg-gray-100 font-poppins">
        <div class="mx-8 max-w-5xl my-8 p-8 bg-white rounded-3xl shadow-lg ">
            <h2 class="mb-8">Booking Order Form</h2>


            <form method="POST" action="{{ route('booking-form') }}" x-ref="bookingForm" @submit.prevent="submitForm" @keydown.enter.prevent>
                @csrf

                {{-- Form Informasi Personal --}}
                <h3 class="mb-2">Informasi Personal</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="grid grid-flow-row auto-rows-max gap-6">
                        <div>
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Nama Lengkap</span>
                                <input type="text" name="nama_lengkap"  x-model="form.nama_lengkap" placeholder="Nama Lengkap" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2" required>
                                @error('nama_lengkap')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div>
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Nomor Whatsapp</span>
                                <input type="text" name="whatsapp"  x-model="form.whatsapp" placeholder="e.g 081234567890" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2" required>
                                @error('whatsapp')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div>
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Kota</span>
                                <div class="mt-2 grid grid-cols-1">
                                    <select name="kota" x-model="form.kota_id" @change="updateNamaKota($event.target.value)" class="col-start-1 row-start-1 w-full h-10 md:h-10 text-base md:text-sm appearance-none border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg pl-2 py-1" required>
                                        <option value="kota">Pilih Kota</option>
                                        @foreach ($kota as $k)
                                            <option value="{{ $k->id_kota }}" data-nama="{{ $k->nama_kota }}" {{ old('id_kota') == $k->id_kota ? 'selected' : '' }}>
                                                {{ $k->nama_kota }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                            @error('id_kota')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label>
                                <span class="bodytext-dark">Lokasi Google Maps</span>
                                <input type="url" name="maps" x-model="form.maps" placeholder="Link Lokasi Google Maps" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2">
                                @error('maps')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div>
                            <label class="block font-medium text-dark-grey">Catatan</label>
                            <textarea name="catatan" x-model="form.catatan" placeholder="Catatan alamat untuk petugas" class="mt-2 w-full h-24 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg p-2"></textarea>
                            @error('catatan')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                        </div>
                    </div>
                    <div class="grid grid-flow-row auto-rows-max gap-6">
                        <div>
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Email</span>
                                <input type="email" name="email" x-model="form.email" placeholder="Email" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2" required>
                                @error('email')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div>
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Alamat Lengkap</span>
                                <textarea name="alamat" x-model="form.alamat" placeholder="Alamat Lengkap" class="mt-2 w-full h-24 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg p-2" required></textarea>
                                @error('alamat')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Informasi Order --}}
                <h3 class="mb-2">Informasi Order</h3>

                <div class="grid grid-flow-row auto-rows-max">
                    <div class="flex flex-col md:flex-row space-y-6 md:space-x-6">
                        <div class="max-w-full md:max-w-64">
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Tanggal Pengerjaan</span>
                                <input type="date" x-ref="tanggal" name="tanggal"  x-model="form.tanggal" :min="minDate()" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2" required>
                                @error('tanggal')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>
                        <div class="max-w-full md:max-w-64">
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Jam Pengerjaan</span>
                                <input type="time" name="waktu" x-model="form.waktu" step="1800" class="mt-2 w-full h-10 border border-gray-400 placeholder focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2" required>
                            </label>
                        </div>
                    </div>
    
                    <div class="mt-6 md:mt-0">
                        <div class="mb-4">
                            <label>
                                <span class="bodytext-dark after:ml-0.5 after:text-red-500 after:content-['*'] ...">Pilih Layanan</span>
                                <p class="text-sm text-gray-500 mb-2">Bisa pilih lebih dari 1 layanan</p>
                            </label>

                            {{-- Dropdown Layanan --}}
                            @include('form.partials.dropdown-layanan')

                            @error('layanan')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <template x-for="(layanan, index) in selectedLayanan" :key="layanan.id">
                                <div class="px-4 py-2 rounded-lg shadow-md/20 bg-cyan-50 relative">
                                    <button type="button" @click="removeLayanan(layanan.id)" class="absolute top-2 right-1 text-gray-500 hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>                                          
                                    </button>
                                    <p class="font-semibold text-cyan md:mr-6" x-text="layanan.nama_rootkategori"></p>
                                    <h4 class="text-xs text-gray-500 mb-2" x-text="layanan.nama_subkategori"></h4>
                                    <p class="bodytext-dark" x-text="'Rp. ' + Intl.NumberFormat('id-ID').format(layanan.harga)"></p>
                                </div>
                            </template>
                        </div>

                        <template x-for="(layanan, index) in selectedLayanan" :key="layanan.id">
                            <input type="hidden" name="layanan[]" :value="layanan.id">
                        </template>
                    </div>
                    <div class="max-w-full md:max-w-48 mb-2">
                        <label>
                            <span class="bodytext-dark after:ml-0.5">Kode Promo</span>
                            <input type="text" name="promo" x-model="form.promo" @change="checkPromoCode" placeholder="Kode Promo" class="mt-2 w-full h-10 border border-gray-400 focus:border-cyan focus:outline focus:outline-cyan rounded-lg px-2">
                        </label>
                    </div>
                </div>

                {{-- Button Booking Sekarang --}}
                <div x-show="errorMessage" x-cloak class="error-message text-red-600 text-sm mt-4">
                    <span x-text="errorMessage"></span>
                </div>
                <button type="button" @click="openBookingDetail" class="w-full mt-8 px-6 py-2 bg-cyan text-white rounded-lg hover:bg-cyan-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                    <span x-show="!isLoading" x-cloak>Booking Sekarang</span>
                    <span x-show="isLoading" x-cloak class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>

                {{-- Debug Bypass Validation --}}
                {{-- <button type="button" class="mt-4 underline text-sm text-gray-500"
                    @click="debugBypassValidation = true; openBookingDetail()">
                    [Test Pop-Up] Buka Booking Detail tanpa isi form
                </button> --}}

                {{-- Modal Booking Detail --}}
                @include('form.partials.modal-booking-detail')

                {{-- Modal Success Booking --}}
                @include('form.partials.modal-success')
            </form>
            
        </div>
        <script type="application/json" id="layanan-data">
            {!! json_encode($layanan) !!}
        </script>
        <script type="application/json" id="kota-data">
            {!! json_encode($kota) !!}
        </script>
    </body>
</html>
