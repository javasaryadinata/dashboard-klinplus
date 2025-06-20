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
        Schema::create('order_detail_petugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order_detail');
            $table->string('id_petugas');

            $table->foreign('id_order_detail')->references('id_order_detail')->on('order_detail')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_petugas');
    }
};
