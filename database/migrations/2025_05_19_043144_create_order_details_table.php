<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('id_order');
            $table->string('id_pricelist', 50); // FK ke layanans.id_pricelist
            $table->time('estimasi_selesai')->nullable();
            $table->string('petugas')->nullable();
            $table->integer('sub_total')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_order')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('id_pricelist')->references('id_pricelist')->on('layanans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
