<template x-if="showSuccessModal">
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-[200] p-4">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full relative">
            <button @click="showSuccessModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-black text-xl">&times;</button>
            <div class="flex flex-col items-center text-center">
                <img src="/images/success-icon.svg" alt="Success" class="w-40 h-40" />
                <h2 class="text-lg font-semibold text-cyan mb-2">Booking Berhasil!</h2>
                <p class="text-sm text-gray-600">
                    Terima kasih telah menggunakan layanan Klinplus!<br>
                    Permintaan booking anda telah terkirim. Petugas kami akan segera menghubungi anda melalui Whatsapp.
                </p>
            </div>
        </div>
    </div>
</template>
