@extends('layouts.app')

@section('title-content')
    <div class="flex items-center gap-2 text-3xl 2xl:text-4xl font-semibold">
        @php
            $isPembayaran = request('ref') === 'pembayaran';
            $backUrl = $isPembayaran ? route('pembayaran.index') : route('orders.index');
            $backLabel = $isPembayaran ? 'Pembayaran' : 'Orders';
        @endphp

        <a href="{{ $backUrl }}" class="hover:underline underline-offset-4 decorations-2 flex items-center gap-2">
            {{ $backLabel }}
        </a>
        <x-lucide-chevron-right class="w-6 h-6 stroke-[3]" />
        <h3 class="font-semibold text-3xl 2xl:text-4xl">Detail Order</h3>
    </div>
@endsection

@section('content')
    <div x-data="{ isLayananModalOpen: false, isPetugasModalOpen: false }" @open-layanan-modal.window="isLayananModalOpen = true"
        @close-layanan-modal.window="isLayananModalOpen = false" @open-petugas-modal.window="isPetugasModalOpen = true"
        @close-petugas-modal.window="isPetugasModalOpen = false">

        <form method="POST" action="{{ route('orders.updateLayanan', $order->id_order) }}">
            @csrf
            @method('PUT')

            <div class="flex flex-row justify-between items-start gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-sky-400 m-0">#{{ $order->id_order }}</h2>
                    @php
                        $badgeColor = match ($order->status) {
                            'Request' => 'badge-secondary',
                            'Scheduled' => 'badge-warning',
                            'Selesai' => 'badge-success',
                            'Canceled' => 'badge-error',
                            default => 'badge-ghost',
                        };
                    @endphp
                    <div class="badge badge-soft {{ $badgeColor }} text-sm px-4">
                        {{ ucfirst($order->status) }}
                    </div>
                </div>

                <div class="flex items-center w-auto">
                    @if (!in_array($order->status, ['Selesai', 'Canceled']))
                        <div class="relative group">
                            <button type="button"
                                onclick="if(confirm('Batalkan order ini?')) { document.getElementById('formCancelOrder').submit(); }"
                                class="p-1.5 bg-white hover:bg-gray-100 text-gray-500 text-sm font-medium rounded-lg transition-colors text-center w-full md:w-auto flex items-center justify-center gap-2 cursor-pointer"
                                title="Tolak Order">
                                <x-lucide-clipboard-x class="w-5 h-5 stroke-[1.4]" />
                            </button>
                        </div>
                        <div class="relative group">
                            <button type="button" onclick="validateApproveOrder()"
                                class="p-1.5 bg-white hover:bg-gray-100 text-gray-500 text-sm font-medium rounded-lg transition-colors text-center w-full md:w-auto flex items-center justify-center gap-2 cursor-pointer"
                                title="Terima Order">
                                <x-lucide-clipboard-check class="w-5 h-5 stroke-[1.4]" />
                            </button>
                        </div>
                    @endif

                    @if (!in_array($order->status, ['Selesai', 'Canceled']))
                        <div class="h-6 w-[1px] bg-gray-300 mx-2"></div>
                    @endif

                    <button type="submit"
                        class="px-4 py-1.5 bg-sky-400 hover:bg-sky-500 text-white text-sm font-medium rounded-lg transition-colors shadow-sm text-center w-full md:w-auto flex items-center justify-center gap-2 cursor-pointer">
                        Simpan
                    </button>
                </div>
            </div>

            <div id="hiddenInputsContainer"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

                <div class="col-span-2 space-y-3">
                    {{-- Box Daftar Layanan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 flex justify-between items-center bg-gray-50/30">
                            <div class="flex items-center gap-2">
                                <h4 class="text-lg font-bold text-gray-700 m-0">Daftar Layanan</h4>
                            </div>
                            <button type="button" @click="$dispatch('open-layanan-modal')"
                                class="bg-sky-400 hover:bg-sky-500 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-1.5 cursor-pointer">
                                <x-lucide-plus class="w-4 h-4 stroke-[2.6]" /> Tambah
                            </button>
                        </div>

                        <div class="overflow-x-auto w-full">
                            <table class="layanan-table w-full text-left" id="layananOrderTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Layanan</th>
                                        <th>Durasi</th>
                                        <th>Petugas</th>
                                        <th>Harga</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-xs text-gray-600">
                                    @foreach ($order->orderDetails as $i => $detail)
                                        @php
                                            $namaPetugas = $detail->petugas->pluck('nama_petugas')->implode(', ');
                                            $idPetugas = $detail->petugas->pluck('id_petugas')->implode(',');
                                        @endphp
                                        <tr data-layanan-id="{{ $detail->id_layanan_subkategori }}"
                                            data-id-order-detail="{{ $detail->id_order_detail }}"
                                            data-petugas-id="{{ $idPetugas }}"
                                            class="hover:bg-gray-50/50 transition-colors">
                                            <td>
                                                {{ $loop->iteration }}
                                                <input type="hidden" name="id_order_detail[]"
                                                    value="{{ $detail->id_order_detail }}">
                                            </td>
                                            <td>
                                                <div class="font-medium! text-sm!">
                                                    {{ $detail->layananSubkategori->rootKategori->nama_rootkategori ?? '-' }}
                                                </div>
                                                <div>{{ $detail->layananSubkategori->nama_subkategori ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="flex items-center gap-1.5">
                                                    <input type="number" name="durasi_layanan[]" min="5" step="5"
                                                        value="{{ $detail->durasi_layanan ?? 60 }}"
                                                        class="durasi-input w-14 border border-gray-200 bg-gray-50 py-1.5 rounded-lg focus:border-1 focus:border-sky-400 outline-none transition-all text-center">
                                                    <span class="text-xs font-medium text-gray-600">Menit</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-red-500 bg-gray-50 border border-gray-200">
                                                    {{ $detail->petugas->count() ? $detail->petugas->pluck('nama_petugas')->implode(', ') : 'Belum Ada' }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 text-sm! font-medium! text-gray-700!">
                                                Rp {{ number_format($detail->subtotal ?? $detail->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button"
                                                        class="btn-edit-petugas text-gray-500 hover:bg-gray-100 p-1.5 rounded-md transition-colors cursor-pointer"
                                                        data-layanan-id="{{ $detail->id_layanan_subkategori }}"
                                                        data-current-petugas="{{ $idPetugas }}"
                                                        data-current-nama-petugas="{{ $namaPetugas }}"
                                                        title="Edit Petugas">
                                                        <x-lucide-user-cog class="w-4 h-4 pointer-events-none stroke-[2]" />
                                                    </button>
                                                    <button type="button"
                                                        class="btn-delete text-red-500 hover:bg-red-50 p-1.5 rounded-md transition-colors cursor-pointer      "
                                                        title="Hapus Layanan">
                                                        <x-lucide-trash-2 class="w-4 h-4 pointer-events-none stroke-[2]" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Box Ringkasan Pembayaran --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-5">
                            <h4 class="text-lg font-bold text-gray-700 m-0">Ringkasan Pembayaran</h4>
                        </div>

                        @php
                            $totalAsli = $order->orderDetails->sum(fn($detail) => $detail->subtotal ?? $detail->harga);
                            $totalHargaInput = old('total_harga', $order->total_harga ?? $totalAsli);
                            $diskonInput = old('diskon', $order->diskon ?? 0);
                        @endphp

                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-4">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status Pembayaran</label>
                                    <div x-data="{
                                        open: false,
                                        value: '{{ $order->metode_pembayaran }}',
                                        get label() { return this.value === 'DP' ? 'DP (Down Payment)' : 'Lunas'; }
                                    }" @click.away="open = false" class="relative w-full max-w-xs">

                                        <input type="hidden" name="metode_pembayaran" :value="value">

                                        <div @click="open = !open"
                                            class="w-full h-9 flex items-center justify-between border bg-gray-50 px-3 py-2 rounded-lg text-sm cursor-pointer transition-all min-w-0"
                                            :class="open ? 'border-sky-400 border-1' : 'border-gray-200 hover:bg-gray-100'">
                                            <span x-text="label" class="text-gray-700 truncate font-normal"></span>
                                            <x-lucide-chevron-down
                                                class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                                                x-bind:class="open ? 'rotate-180' : ''" />
                                        </div>

                                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                            class="absolute left-0 top-full z-[60] w-full p-1.5 shadow-xl bg-white border border-gray-100 rounded-xl mt-1 overflow-hidden">
                                            <ul class="flex flex-col gap-1">
                                                <li>
                                                    <a href="#" @click.prevent="value = 'DP'; open = false"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="value === 'DP' ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        DP (Down Payment)
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" @click.prevent="value = 'Lunas'; open = false"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="value === 'Lunas' ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        Lunas
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Pembayaran</label>
                                    <div x-data="{
                                        open: false,
                                        value: '{{ $order->tipe_pembayaran }}'
                                    }" @click.away="open = false" class="relative w-full max-w-xs">

                                        <input type="hidden" name="tipe_pembayaran" :value="value">

                                        <div @click="open = !open"
                                            class="w-full h-9 flex items-center justify-between border bg-gray-50 px-3 py-2 rounded-lg text-sm cursor-pointer transition-all min-w-0"
                                            :class="open ? 'border-sky-400 border-1' : 'border-gray-200 hover:bg-gray-100'">
                                            <span x-text="value" class="text-gray-700 truncate font-normal"></span>
                                            <x-lucide-chevron-down
                                                class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                                                x-bind:class="open ? 'rotate-180' : ''" />
                                        </div>

                                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                            class="absolute left-0 top-full z-[60] w-full p-1.5 shadow-xl bg-white border border-gray-100 rounded-xl mt-1 overflow-hidden">
                                            <ul class="flex flex-col gap-1">
                                                <li>
                                                    <a href="#" @click.prevent="value = 'Transfer'; open = false"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="value === 'Transfer' ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        Transfer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" @click.prevent="value = 'Cash'; open = false"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="value === 'Cash' ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        Cash
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div
                                class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-center h-full">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-700 font-medium">Subtotal Layanan</span>
                                        <span class="text-gray-500 font-medium" id="display_subtotal">Rp
                                            {{ number_format($totalAsli, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-700 font-medium">Diskon</span>
                                        <div class="text-right">
                                            <input type="hidden" name="diskon" id="diskon_hidden"
                                                value="{{ $diskonInput }}">
                                            <input type="text" id="diskon_input"
                                                class="w-26 border border-gray-200 px-1.5 py-1 rounded-md text-right focus:border-sky-400 outline-none text-red-500 font-medium"
                                                value="{{ $diskonInput > 0 ? 'Rp ' . number_format($diskonInput, 0, ',', '.') : '' }}"
                                                placeholder="Rp 0">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-t border-gray-200 pt-1">
                                        <span class="text-gray-700 font-bold">Grand Total</span>
                                        <div class="text-right">
                                            <input type="hidden" name="total_harga" id="total_harga_input"
                                                value="{{ $totalHargaInput }}">
                                            <span class="text-xl font-bold text-emerald-500" id="totalHargaRupiah">Rp
                                                {{ number_format($totalHargaInput, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="space-y-3">
                    {{-- # Box Informasi Pelanggan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
                        <div class="flex items-center justify-between gap-2 mb-4 relative z-10">
                            <div class="flex items-center gap-2">
                                <h4 class="text-lg font-bold text-gray-700 m-0">Informasi Pelanggan</h4>
                            </div>

                            @if ($order->pelanggan && $order->pelanggan->telp_pelanggan)
                                @php
                                    $waNumber = preg_replace('/[^0-9]/', '', $order->pelanggan->telp_pelanggan);

                                    if (substr($waNumber, 0, 1) == '0') {
                                        $waNumber = '62' . substr($waNumber, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#25D366] hover:bg-[#1ebd5a] text-white text-sm font-medium rounded-lg transition-colors shadow-sm outline-none">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.405-.883-.733-1.48-1.64-1.653-1.938-.173-.298-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    Chat Pelanggan
                                </a>
                            @else
                                <span class="text-xs text-gray-400 font-medium italic">Nomor WA Tidak Tersedia</span>
                            @endif
                        </div>

                        <div class="space-y-4 relative z-10">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Nama</label>
                                <p class="text-sm text-gray-700">{{ $order->pelanggan->nama_pelanggan }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Alamat</label>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $order->alamat_lokasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- # Box Jadwal Pengerjaan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-5">
                            <h4 class="text-lg font-bold text-gray-700 m-0">Jadwal Pengerjaan</h4>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pengerjaan</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <x-lucide-calendar class="h-4 w-4 stroke-[2]" />
                                    </div>
                                    <input type="text" name="tanggal_pengerjaan" id="input_tanggal_pengerjaan"
                                        value="{{ $order->tanggal_pengerjaan }}" required placeholder="Pilih Tanggal..."
                                        x-data x-init="flatpickr($el, {
                                            dateFormat: 'Y-m-d',
                                            altInput: true,
                                            altFormat: 'd F Y',
                                            locale: 'id',
                                            minDate: 'today'
                                        })"
                                        class="w-full max-w-xs h-9 bg-gray-50 border border-gray-200 pl-9 pr-3 rounded-lg text-sm focus:border-sky-400 focus:border-1 outline-none transition-all cursor-pointer text-gray-700">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu Mulai</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <x-lucide-clock class="h-4 w-4" />
                                    </div>
                                    <input type="text" name="jam_pengerjaan" id="input_jam_pengerjaan"
                                        value="{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}"
                                        required placeholder="Pilih Jam..." x-data x-init="flatpickr($el, {
                                            enableTime: true,
                                            noCalendar: true,
                                            dateFormat: 'H:i',
                                            time_24hr: true
                                        })"
                                        class="w-full max-w-xs h-9 bg-gray-50 border border-gray-200 pl-9 pr-3 rounded-lg text-sm focus:border-sky-400 focus:border-1 outline-none transition-all cursor-pointer text-gray-700">
                                </div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-yellow-500 font-medium">Estimasi Durasi</span>
                                    <span class="font-medium text-yellow-500"><input type="text" id="estimasi-durasi"
                                            class="bg-transparent w-24 text-right outline-none cursor-default"
                                            value="0" readonly></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-yellow-500 font-medium">Estimasi Selesai</span>
                                    <span class="font-bold text-yellow-500"><input type="text" id="jam-selesai"
                                            class="bg-transparent w-24 text-right outline-none cursor-default"
                                            value="" readonly></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-sticky-note class="w-4 h-4 text-yellow-500" />
                            <h4 class="text-sm font-semibold text-yellow-500 m-0">Catatan Khusus</h4>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed italic">
                            {{ $order->catatan ?? 'Tidak ada catatan tambahan dari pelanggan untuk order ini.' }}
                        </p>
                    </div>

                </div>
            </div>
        </form>

        {{-- MODAL PILIH LAYANAN --}}
        <template x-teleport="body">
            <div x-show="isLayananModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div x-show="isLayananModalOpen" x-transition.opacity @click="isLayananModalOpen = false"
                    class="fixed inset-0 backdrop-brightness-75 backdrop-blur-xs transition-opacity cursor-pointer"></div>

                <div class="flex min-h-full items-center justify-center p-4 pointer-events-none">
                    <div x-show="isLayananModalOpen" x-transition
                        class="relative transform bg-white text-left shadow-lg transition-all sm:my-8 w-full max-w-4xl rounded-2xl flex flex-col max-h-[85vh] overflow-hidden pointer-events-auto">

                        <input type="hidden" id="editMode" value="0">
                        <input type="hidden" id="currentLayananId">

                        <div class="p-4 border-b border-gray-100 bg-white sticky top-0 z-10 shrink-0 shadow-md">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-sky-400">Pilih Layanan</h3>
                                <button @click="isLayananModalOpen = false"
                                    class="text-gray-400 hover:bg-gray-100 bg-transparent rounded-md p-[2px]">
                                    <x-lucide-x class="w-5 h-5 stroke-[2]" />
                                </button>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-lucide-search class="h-4 w-4 text-gray-400 stroke-[2]" />
                                </div>
                                <input type="text" id="searchLayanan" placeholder="Cari layanan..."
                                    class="w-full h-9 border-gray-300 border pl-10 pr-10 py-2.5 rounded-xl text-sm focus:border-1 focus:border-sky-400 outline-none transition-all shadow-sm">

                                <button type="button" id="clearSearchLayananBtn" style="display: none;"
                                    class="absolute inset-y-0 right-0 m-2 px-0.5 rounded-sm flex items-center hover:bg-gray-100 text-gray-400 transition-colors">
                                    <x-lucide-x class="h-3.5 w-3.5 stroke-[2.5]" />
                                </button>
                            </div>
                        </div>

                        <div class="p-6 overflow-y-auto flex-1 bg-gray-50/50" id="layananCardContainer">
                            @php
                                $groupedLayanans = $layanans->groupBy(function ($item) {
                                    return $item->rootKategori->nama_rootkategori ?? 'Lainnya';
                                });
                            @endphp
                            @foreach ($groupedLayanans as $rootName => $subs)
                                <div class="mb-6 last:mb-0 layanan-group">
                                    <p class="font-bold text-lg text-sky-400 mb-3 tracking-wide">{{ $rootName }}</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        @foreach ($subs as $sub)
                                            <div class="layanan-card border border-gray-100 hover:border-sky-400 hover:border-1 shadow-sm transition-all rounded-xl overflow-hidden flex flex-col group"
                                                data-id="{{ $sub->id }}"
                                                data-nama="{{ $rootName . ' - ' . $sub->nama_subkategori }}"
                                                data-harga="{{ $sub->harga }}" data-durasi="{{ $sub->durasi ?? 0 }}">
                                                <div class="w-full h-28 bg-gray-100 flex items-center justify-center relative overflow-hidden group-hover:bg-sky-50 transition-colors">
                                                    <x-lucide-image class="w-8 h-8 text-gray-300 group-hover:text-sky-300 transition-colors" />
                                                </div>

                                                <div class="p-3 flex flex-col flex-1">
                                                    <span class="text-sm font-semibold text-gray-700 mb-1 leading-snug transition-colors">{{ $sub->nama_subkategori }}</span>
                                                    <span class="text-xs font-medium text-gray-500 mt-auto pt-2">Rp {{ number_format($sub->harga, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- MODAL EDIT PETUGAS --}}
        <template x-teleport="body">
            <div x-show="isPetugasModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                {{-- Backdrop --}}
                <div x-show="isPetugasModalOpen" x-transition.opacity @click="isPetugasModalOpen = false"
                    class="fixed inset-0 backdrop-brightness-75 backdrop-blur-xs cursor-pointer"></div>
                <div class="flex min-h-full items-center justify-center p-4 text-center pointer-events-none">
                    <div x-show="isPetugasModalOpen" x-transition
                        class="relative transform bg-white text-left shadow-2xl transition-all w-full max-w-sm rounded-2xl pointer-events-auto">

                        <div class="px-4 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h5 class="font-bold text-lg text-sky-400">Edit Petugas</h5>
                            <button @click="isPetugasModalOpen = false"
                                class="text-gray-400 hover:bg-gray-100 bg-transparent rounded-md p-[2px]">
                                <x-lucide-x class="w-5 h-5 stroke-[2]" />
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <input type="hidden" id="editPetugasLayananId">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Petugas 1 <span
                                        class="text-red-500">*</span></label>

                                <select id="petugasSelect1" class="hidden" required>
                                    <option value="" selected disabled>Pilih Petugas 1</option>
                                    @foreach ($petugas as $ptg)
                                        <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">
                                            {{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}</option>
                                    @endforeach
                                </select>

                                <div x-data="{
                                    open: false,
                                    selectedValue: '',
                                    selectedLabel: 'Pilih Petugas 1',
                                    init() {
                                        const select = document.getElementById('petugasSelect1');
                                        this.updateFromSelect(select);
                                        select.addEventListener('change', () => this.updateFromSelect(select));
                                        document.getElementById('petugasSelect2').addEventListener('change', () => this.updateCounter++);
                                    },
                                    updateFromSelect(select) {
                                        this.selectedValue = select.value;
                                        const option = select.options[select.selectedIndex];
                                        this.selectedLabel = option && option.value ? option.text : 'Pilih Petugas 1';
                                    },
                                    selectOption(value) {
                                        const select = document.getElementById('petugasSelect1');
                                        select.value = value;
                                        select.dispatchEvent(new Event('change'));
                                        this.open = false;
                                    },
                                    getAvailableOptions() {
                                        this.updateCounter;
                                        const otherValue = document.getElementById('petugasSelect2').value;
                                        return Array.from(document.getElementById('petugasSelect1').options)
                                                    .filter(opt => opt.value !== '' && opt.value !== otherValue);
                                    }
                                }" @click.away="open = false" class="relative w-full">

                                    <div @click="open = !open"
                                        class="w-full h-9 flex items-center justify-between border bg-gray-50 px-2 py-2 rounded-lg text-sm cursor-pointer transition-all min-w-0"
                                        :class="open ? 'border-sky-400 border-1' : 'border-gray-200 hover:bg-gray-100'">
                                        <span x-text="selectedLabel" class="truncate font-normal"
                                            :class="selectedValue ? 'text-gray-700' : 'text-gray-400'"></span>
                                        <x-lucide-chevron-down
                                            class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                                            x-bind:class="open ? 'rotate-180' : ''" />
                                    </div>

                                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                        class="absolute left-0 top-full z-[60] w-full p-1.5 shadow-xl bg-white border border-gray-100 rounded-xl mt-1 overflow-y-auto max-h-48">
                                        <ul class="flex flex-col gap-1">
                                            <template
                                                x-for="option in getAvailableOptions()">
                                                <li>
                                                    <a href="#" @click.prevent="selectOption(option.value)"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="selectedValue === option.value ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        <span x-text="option.text"></span>
                                                    </a>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Petugas 2</label>

                                <select id="petugasSelect2" class="hidden">
                                    <option value="" selected>Pilih Petugas 2
                                    <option>
                                        @foreach ($petugas as $ptg)
                                    <option value="{{ $ptg->id_petugas }}" data-nama="{{ $ptg->nama_petugas }}">
                                        {{ $ptg->id_petugas }} - {{ $ptg->nama_petugas }}</option>
                                    @endforeach
                                </select>

                                <div x-data="{
                                    open: false,
                                    selectedValue: '',
                                    selectedLabel: 'Pilih Petugas 2',
                                    updateCounter: 0,
                                    init() {
                                        const select = document.getElementById('petugasSelect2');
                                        this.updateFromSelect(select);
                                        select.addEventListener('change', () => this.updateFromSelect(select));
                                        document.getElementById('petugasSelect1').addEventListener('change', () => this.updateCounter++);
                                    },
                                    updateFromSelect(select) {
                                        this.selectedValue = select.value;
                                        const option = select.options[select.selectedIndex];
                                        this.selectedLabel = option && option.value ? option.text : 'Pilih Petugas 2';
                                        this.updateCounter++;
                                    },
                                    selectOption(value) {
                                        const select = document.getElementById('petugasSelect2');
                                        select.value = value;
                                        select.dispatchEvent(new Event('change'));
                                        this.open = false;
                                    },
                                    getAvailableOptions() {
                                        this.updateCounter; // Pancingan reaktivitas Alpine
                                        const otherValue = document.getElementById('petugasSelect1').value;
                                        return Array.from(document.getElementById('petugasSelect2').options)
                                                    .filter(opt => opt.value !== '' && opt.value !== otherValue);
                                    }
                                }" @click.away="open = false" class="relative w-full">

                                    <div @click="open = !open"
                                        class="w-full h-9 flex items-center justify-between border bg-gray-50 px-2 py-2 rounded-lg text-sm cursor-pointer transition-all min-w-0"
                                        :class="open ? 'border-sky-400 border-1' : 'border-gray-200 hover:bg-gray-100'">
                                        <span x-text="selectedLabel" class="truncate font-normal"
                                            :class="selectedValue ? 'text-gray-700' : 'text-gray-400'"></span>
                                        <x-lucide-chevron-down
                                            class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                                            x-bind:class="open ? 'rotate-180' : ''" />
                                    </div>

                                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                                        class="absolute left-0 top-full z-[60] w-full p-1.5 shadow-xl bg-white border border-gray-100 rounded-xl mt-1 overflow-y-auto max-h-48">
                                        <ul class="flex flex-col gap-1">
                                            <li>
                                                <a href="#" @click.prevent="selectOption('')"
                                                    class="block px-3 py-2 rounded-lg text-sm transition-colors text-red-400 hover:bg-red-50 font-normal">
                                                    Kosongkan
                                                </a>
                                            </li>
                                            <template
                                                x-for="option in getAvailableOptions()">
                                                <li>
                                                    <a href="#" @click.prevent="selectOption(option.value)"
                                                        class="block px-3 py-2 rounded-lg text-sm transition-colors"
                                                        :class="selectedValue === option.value ? 'bg-sky-50 text-gray-700 font-medium' : 'text-gray-600 hover:bg-slate-100 font-normal'">
                                                        <span x-text="option.text"></span>
                                                    </a>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Petugas</label>
                                <input type="text"
                                    class="w-full h-9 bg-gray-50 border border-gray-200 text-gray-400 px-4 py-2.5 rounded-lg text-sm outline-none"
                                    id="petugasInput" readonly>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" @click="isPetugasModalOpen = false"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-sm text-gray-500 rounded-lg font-medium transition-colors cursor-pointer">Batal</button>
                            <button type="button" id="savePetugasBtn"
                                class="px-4 py-2 bg-sky-400 hover:bg-sky-500 text-sm text-white rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        @if (!in_array($order->status, ['Selesai', 'Canceled']))
            <form id="formApproveOrder" action="{{ route('orders.approve', $order->id_order) }}" method="POST"
                class="hidden">
                @csrf
            </form>
            <form id="formCancelOrder" action="{{ route('orders.approve', $order->id_order) }}" method="POST"
                class="hidden">
                @csrf
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfigurasi Toastr
            toastr.options = {
                "closeButton": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "4000",
            };

            @if (session('success'))
                toastr.success("{{ session('success') }}", "Berhasil!");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}", "Gagal!");
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Validasi Gagal!");
                @endforeach
            @endif

            const elements = {
                jamMulaiInput: document.getElementById('jam_mulai'),
                tableBody: document.querySelector('#layananOrderTable tbody'),
                hiddenInputsContainer: document.getElementById('hiddenInputsContainer'),
                editModeInput: document.getElementById('editMode'),
                petugasSelect1: document.getElementById('petugasSelect1'),
                petugasSelect2: document.getElementById('petugasSelect2'),
                petugasInput: document.getElementById('petugasInput'),
                editPetugasLayananId: document.getElementById('editPetugasLayananId'),
                savePetugasBtn: document.getElementById('savePetugasBtn')
            };

            // Dispatch Alpine pengganti "new bootstrap.Modal().hide()" 
            const modalController = {
                hideLayanan: () => window.dispatchEvent(new CustomEvent('close-layanan-modal')),
                showPetugas: () => window.dispatchEvent(new CustomEvent('open-petugas-modal')),
                hidePetugas: () => window.dispatchEvent(new CustomEvent('close-petugas-modal'))
            };

            addDurasiInputListeners();
            updateTotalDurasiDanJamSelesai();
            syncHiddenInputsWithTable();

            function addDurasiInputListeners() {
                document.querySelectorAll('.durasi-input').forEach(input => {
                    input.addEventListener('input', updateTotalDurasiDanJamSelesai);
                    input.addEventListener('input', syncHiddenInputsWithTable);
                });
            }

            function updateTotalDurasiDanJamSelesai() {
                let totalDurasi = 0;
                document.querySelectorAll('.durasi-input').forEach(input => {
                    totalDurasi += parseInt(input.value) || 0;
                });
                document.getElementById('estimasi-durasi').value = totalDurasi + ' Menit';

                const inputJamEl = document.getElementById('input_jam_pengerjaan');
                const jamMulai = inputJamEl ? inputJamEl.value :
                    "{{ \Carbon\Carbon::parse($order->jam_pengerjaan)->format('H:i') }}";

                if (jamMulai && jamMulai.includes(':')) {
                    const [jam, menit] = jamMulai.split(':').map(Number);
                    const totalMenit = jam * 60 + menit + totalDurasi;

                    const hasilJam = String(Math.floor(totalMenit / 60) % 24).padStart(2, '0');
                    const hasilMenit = String(totalMenit % 60).padStart(2, '0');

                    document.getElementById('jam-selesai').value = `${hasilJam}:${hasilMenit} WIB`;
                }
            }

            const inputJamPengerjaan = document.getElementById('input_jam_pengerjaan');
            if (inputJamPengerjaan) {
                inputJamPengerjaan.addEventListener('change', updateTotalDurasiDanJamSelesai);
            }

            function syncHiddenInputsWithTable() {
                elements.hiddenInputsContainer.innerHTML = '';
                document.querySelectorAll('#layananOrderTable tbody tr').forEach((row, index) => {
                    const idOrderDetailInput = row.querySelector('input[name="id_order_detail[]"]');
                    const idOrderDetail = idOrderDetailInput ? idOrderDetailInput.value : '';
                    const layananId = row.dataset.layananId;
                    const durasi = row.querySelector('.durasi-input')?.value || '60';
                    const petugasId = row.dataset.petugasId || '';
                    const harga = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '').trim() ||
                        '0';
                    const petugasArr = petugasId.split(',').filter(Boolean);

                    elements.hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="id_order_detail[]"  value="${idOrderDetail}">
                <input type="hidden" name="layanans[]"         value="${layananId}">
                <input type="hidden" name="durasi_layanan[]"   value="${durasi}">
                <input type="hidden" name="subtotals[]"        value="${harga}">
            `);

                    petugasArr.forEach(pid => {
                        elements.hiddenInputsContainer.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="petugas[${index}][]" value="${pid}">
                `);
                    });
                });
            }

            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                syncHiddenInputsWithTable();
                this.submit();
            });

            function updateTotalHarga() {
                const rows = elements.tableBody.querySelectorAll('tr');
                let totalHarga = 0;
                rows.forEach(row => {
                    const subtotalText = row.cells[4].textContent.replace('Rp', '').replace(/\./g, '');
                    totalHarga += parseInt(subtotalText) || 0;
                });

                const displaySubtotal = document.getElementById('display_subtotal');
                if (displaySubtotal) {
                    displaySubtotal.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
                }

                const diskonHidden = document.getElementById('diskon_hidden');
                const diskonValue = diskonHidden ? (parseInt(diskonHidden.value) || 0) : 0;
                const totalSetelahDiskon = Math.max(0, totalHarga - diskonValue);

                const totalHargaInput = document.getElementById('total_harga_input');
                const totalHargaRupiah = document.getElementById('totalHargaRupiah');
                if (totalHargaInput) totalHargaInput.value = 'Rp ' + totalSetelahDiskon;
                if (totalHargaRupiah) totalHargaRupiah.textContent = 'Rp ' + totalSetelahDiskon.toLocaleString(
                    'id-ID');
            }

            function updateTableNumbering() {
                const rows = elements.tableBody.querySelectorAll('tr');
                rows.forEach((row, idx) => {
                    row.cells[0].textContent = idx + 1;
                });
            }

            elements.tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.btn-delete')) {
                    if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
                        e.target.closest('tr').remove();
                        updateTableNumbering();
                        updateTotalHarga();
                        syncHiddenInputsWithTable();
                    }
                }
            });

            // Fitur Search Layanan
            const searchInput = document.getElementById('searchLayanan');
            const clearSearchLayananBtn = document.getElementById('clearSearchLayananBtn');

            if (searchInput) {
                if (clearSearchLayananBtn) {
                    clearSearchLayananBtn.addEventListener('click', function() {
                        searchInput.value = '';
                        this.style.display = 'none';
                        searchInput.dispatchEvent(new Event('input'));
                        searchInput.focus();
                    });
                }

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    if (clearSearchLayananBtn) {
                        clearSearchLayananBtn.style.display = searchTerm.length > 0 ? 'flex' : 'none';
                    }

                    document.querySelectorAll('.layanan-group').forEach(group => {
                        let hasVisibleCard = false;
                        group.querySelectorAll('.layanan-card').forEach(card => {
                            const namaLayanan = card.dataset.nama.toLowerCase();
                            if (namaLayanan.includes(searchTerm)) {
                                card.style.display = '';
                                hasVisibleCard = true;
                            } else {
                                card.style.display = 'none';
                            }
                        });
                        group.style.display = hasVisibleCard ? '' : 'none';
                    });
                });
            }

            // Klik Card Tambah Layanan
            document.querySelectorAll('.layanan-card').forEach(card => {
                card.addEventListener('click', function() {
                    const isEditMode = elements.editModeInput.value === '1';
                    const layananId = this.dataset.id;
                    const namaLayanan = this.dataset.nama;
                    const harga = this.dataset.harga;
                    const parts = namaLayanan.split(' - ');
                    const kategori = parts[0];
                    const subkategori = parts.slice(1).join(' - ') || namaLayanan;

                    const existingRow = elements.tableBody.querySelector(
                        `tr[data-layanan-id="${layananId}"]`);
                    if (existingRow && !isEditMode) {
                        toastr.error("Layanan sudah ada!");
                        return;
                    }

                    const newRow = document.createElement('tr');
                    newRow.className = "hover:bg-gray-50/50 transition-colors";
                    newRow.dataset.layananId = layananId;
                    newRow.innerHTML = `
                <td></td>
                <td>
                    <div class="font-medium! text-sm!">${kategori}</div>
                    <div>${subkategori}</div>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <input type="number" class="durasi-input w-14 border border-gray-200 bg-gray-50 py-1.5 rounded-lg focus:border-1 focus:border-sky-400 outline-none transition-all text-center" name="durasi_layanan[]" min="5" step="5" value="60">
                        <span class="text-xs font-medium text-gray-500">Menit</span>
                    </div>
                </td>
                <td>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-red-500 bg-gray-50 border border-gray-200">
                        Belum Ada
                    </span>
                </td>
                <td class="px-5 py-4 text-sm! font-medium! text-gray-700!">
                    Rp ${parseInt(harga).toLocaleString('id-ID')}
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <button type="button" class="btn-edit-petugas text-gray-500 hover:bg-gray-100 p-1.5 rounded-md transition-colors cursor-pointer" data-layanan-id="${layananId}" title="Edit Petugas">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 pointer-events-none"><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m21.7 16.4-.9-.3"/><path d="m15.2 13.9-.9-.3"/><path d="m16.6 18.7.3-.9"/><path d="m19.1 12.2.3-.9"/><path d="m19.6 18.7-.4-1"/><path d="m16.8 12.3-.4-1"/><path d="m14.3 16.6 1-.4"/><path d="m20.7 13.8 1-.4"/></svg>
                        </button>
                        <button type="button" class="btn-delete text-red-500 hover:bg-red-50 p-1.5 rounded-md transition-colors cursor-pointer" title="Hapus Layanan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 pointer-events-none"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </div>
                </td>
            `;

                    if (isEditMode && existingRow) {
                        existingRow.replaceWith(newRow);
                    } else {
                        elements.tableBody.appendChild(newRow);
                    }

                    updateTableNumbering();
                    addDurasiInputListeners();
                    updateTotalDurasiDanJamSelesai();
                    updateTotalHarga();

                    if (searchInput) {
                        searchInput.value = '';
                        if (clearSearchLayananBtn) clearSearchLayananBtn.style.display = 'none';
                    }

                    document.querySelectorAll('.layanan-group, .layanan-card').forEach(el => el
                        .style.display = '');

                    syncHiddenInputsWithTable();
                    modalController.hideLayanan();
                    toastr.success("Layanan berhasil ditambahkan ke tabel", "Berhasil!");
                });
            });

            document.getElementById('petugasSelect1').addEventListener('change', updateNamaPetugas);
            document.getElementById('petugasSelect2').addEventListener('change', updateNamaPetugas);

            function updateNamaPetugas() {
                let nama = '';
                if (elements.petugasSelect1.value) nama += elements.petugasSelect1.options[elements.petugasSelect1
                    .selectedIndex].getAttribute('data-nama');
                if (elements.petugasSelect2.value) nama += ', ' + elements.petugasSelect2.options[elements
                    .petugasSelect2.selectedIndex].getAttribute('data-nama');
                elements.petugasInput.value = nama;
            }

            // Panggil Modal Edit Petugas
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-edit-petugas')) {
                    const btn = e.target.closest('.btn-edit-petugas');
                    const layananId = btn.dataset.layananId;
                    const currentPetugas = btn.dataset.currentPetugas || '';
                    const currentNamaPetugas = btn.dataset.currentNamaPetugas || '';

                    elements.editPetugasLayananId.value = layananId;

                    elements.petugasSelect1.value = "";
                    elements.petugasSelect2.value = "";
                    elements.petugasInput.value = currentNamaPetugas;

                    if (currentPetugas) {
                        const petugasArr = currentPetugas.split(',');
                        if (petugasArr[0]) elements.petugasSelect1.value = petugasArr[0].trim();
                        if (petugasArr[1]) elements.petugasSelect2.value = petugasArr[1].trim();
                    }

                    updateNamaPetugas();

                    elements.petugasSelect1.dispatchEvent(new Event('change'));
                    elements.petugasSelect2.dispatchEvent(new Event('change'));

                    modalController.showPetugas();
                    syncHiddenInputsWithTable();
                }
            });

            // Save Edit Petugas
            elements.savePetugasBtn.addEventListener('click', function() {
                const layananId = elements.editPetugasLayananId.value;
                const petugasId1 = elements.petugasSelect1.value;
                const petugasId2 = elements.petugasSelect2.value;
                const namaPetugas1 = petugasId1 ? elements.petugasSelect1.options[elements.petugasSelect1
                    .selectedIndex].getAttribute('data-nama') : '';
                const namaPetugas2 = petugasId2 ? elements.petugasSelect2.options[elements.petugasSelect2
                    .selectedIndex].getAttribute('data-nama') : '';
                let displayText = namaPetugas1;
                if (namaPetugas2) displayText += ', ' + namaPetugas2;

                const row = document.querySelector(`tr[data-layanan-id="${layananId}"]`);
                if (row) {
                    row.cells[3].innerHTML =
                        `<span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md bg-gray-100 border border-gray-200">${displayText}</span>`;
                    row.dataset.petugasId = [petugasId1, petugasId2].filter(Boolean).join(',');
                    row.dataset.namaPetugas = displayText;
                    const editBtn = row.querySelector('.btn-edit-petugas');
                    if (editBtn) {
                        editBtn.dataset.currentPetugas = [petugasId1, petugasId2].filter(Boolean).join(',');
                        editBtn.dataset.currentNamaPetugas = displayText;
                    }
                }

                syncHiddenInputsWithTable();
                modalController.hidePetugas();
            });

            // Format Rupiah
            const diskonInput = document.getElementById('diskon_input');
            const diskonHidden = document.getElementById('diskon_hidden');
            const totalInput = document.getElementById('total_harga_input');
            const totalDisplay = document.getElementById('totalHargaRupiah');

            function formatRupiah(val) {
                let num = parseInt(String(val).replace(/\D/g, '')) || 0;
                return num === 0 ? '' : 'Rp ' + num.toLocaleString('id-ID');
            }

            if (diskonInput && totalInput) {
                diskonInput.addEventListener('input', function(e) {
                    let rawValue = parseInt(this.value.replace(/\D/g, '')) || 0;

                    this.value = formatRupiah(rawValue);

                    if (diskonHidden) diskonHidden.value = rawValue;

                    var diskon = rawValue;
                    var hargaSetelahDiskon = Math.max(0, {{ $totalAsli }} - diskon);

                    totalInput.value = hargaSetelahDiskon;
                    totalDisplay.textContent = 'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID');
                });
            }

            // Validasi Terima Order
            window.validateApproveOrder = function() {
                const rows = document.querySelectorAll('#layananOrderTable tbody tr');

                // 1. Pagar Pertama: Cek apakah ada layanan di tabel
                if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td').colSpan == 10)) {
                    toastr.warning("Order tidak bisa diterima! Tambahkan minimal 1 layanan terlebih dahulu.",
                        "Peringatan");
                    return;
                }

                // 2. Pagar Kedua: Cek apakah semua layanan sudah diatur petugasnya
                let adaPetugasKosong = false;
                rows.forEach(row => {
                    const petugasId = row.dataset.petugasId;
                    // Jika attribute data-petugas-id kosong, berarti belum ada yang ditugaskan
                    if (!petugasId || petugasId.trim() === '') {
                        adaPetugasKosong = true;

                        // Opsional: Kasih efek visual merah di baris yang salah biar admin gampang nyarinya
                        row.classList.add('bg-red-50/50');
                        setTimeout(() => row.classList.remove('bg-red-50/50'), 2000);
                    }
                });

                if (adaPetugasKosong) {
                    toastr.error(
                        "Terdapat layanan yang belum ditugaskan kepada Petugas! Silakan edit petugas terlebih dahulu.",
                        "Tindakan Ditolak");
                    return;
                }

                // 3. Pagar Ketiga: Lolos, minta konfirmasi dan submit
                if (confirm('Semua petugas sudah siap. Terima dan jadwalkan order ini?')) {
                    document.getElementById('formApproveOrder').submit();
                }
            };
        });
    </script>
@endpush
