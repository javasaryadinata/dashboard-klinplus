<?php
use App\Http\Controllers\BookingFormController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

// Route dasar
Route::get('/', function () {
    return view('welcome');
});

// Route untuk testing
// Route::get('/cek', function () {
//     return view('coba');
// });

// Route untuk view statis
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::post('/layanan/kategori', [LayananController::class, 'storeRootKategori'])->name('layanan.kategori.store');
Route::delete('/layanan/kategori/{id}', [LayananController::class, 'destroyRootKategori'])->name('layanan.kategori.destroy');

Route::get('orders/{order}/detail', [OrderController::class, 'show'])->name('orders.detail');
Route::get('/orders/{id_order}/invoice/pdf', [OrderController::class, 'invoicePdf'])->name('orders.invoicePdf');
Route::post('orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');
Route::post('orders/{order}/update-layanan', [OrderController::class, 'updateLayanan'])->name('orders.updateLayanan');
Route::put('orders/{order}/update-layanan', [OrderController::class, 'updateLayanan'])->name('orders.updateLayanan');
Route::post('/orders/{id_order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/booking-form', [BookingFormController::class, 'showBookingForm'])->name('booking.form');
Route::post('/booking-form', [BookingFormController::class, 'storeBooking'])->name('booking.form.submit');

Route::get('/promo/check', [BookingFormController::class, 'checkPromo'])->name('promo.check');

// Route resource
Route::resource('orders', OrderController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('layanan', LayananController::class);
Route::resource('petugas', PetugasController::class)
    ->parameters(['petugas' => 'petugas:id_petugas']);

// Route Jadwal
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/{id_order}', [JadwalController::class, 'show'])->name('jadwal.show');
Route::get('/jadwal/{id_order}/working-order', [JadwalController::class, 'downloadWorkingOrder'])->name('jadwal.workingOrder');
Route::post('jadwal/{id_order}/do-reschedule', [JadwalController::class, 'doReschedule'])->name('jadwal.doReschedule');
Route::put('/jadwal/{id_order}/reschedule-update', [JadwalController::class, 'rescheduleUpdate'])->name('jadwal.rescheduleUpdate');
Route::put('/jadwal/{id_order}', [JadwalController::class, 'update'])->name('jadwal.update');
Route::post('/jadwal/{id_order}/selesai', [JadwalController::class, 'selesai'])->name('jadwal.selesai');
Route::delete('/jadwal/{id_order}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
Route::get('/orders/{id_order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/pembayaran/{id_order}/invoice', [PembayaranController::class, 'invoice'])->name('pembayaran.invoice');
Route::post('/pembayaran/{id_order}/lunas', [PembayaranController::class, 'setLunas'])->name('pembayaran.setLunas');
Route::post('/pembayaran/{id_order}/close', [PembayaranController::class, 'close'])->name('pembayaran.close');

Route::get('/riwayat', [App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat.index');

Route::get('/invoice-preview/{order_id}', function($order_id) {
    // Ambil order sesuai struktur aplikasi kamu
    $order = \App\Models\Order::with([
        'pelanggan',
        'orderDetails.layananSubkategori.rootKategori',
        'orderDetails.petugas'
    ])->where('id_order', $order_id)->firstOrFail();

    return view('emails.invoice', compact('order'));
});
Route::get('/invoice/preview/{id_order}', [OrderController::class, 'previewInvoice']);
Route::get('/working-order/preview/{id_order}', [JadwalController::class, 'previewWorkingOrder'])->name('working_order.preview');
