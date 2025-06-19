<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('id_order', 20);
            $table->string('nama_pelanggan');
            $table->string('alamat');
            $table->string('gmaps')->nullable();
            $table->text('catatan')->nullable();
            $table->date('tanggal_pengerjaan');
            $table->time('waktu_pengerjaan');
            $table->integer('durasi');
            $table->time('waktu_selesai');
            $table->string('nama_petugas')->nullable();
            $table->string('status_pembayaran');
            $table->timestamps();

            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
