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
        Schema::table('order_detail', function (Blueprint $table) {
            // Hapus foreign key dengan nama spesifik
            $table->dropForeign('fk_order_detail_petugas');

            // Hapus kolom jika masih ada
            if (Schema::hasColumn('order_detail', 'id_petugas')) {
                $table->dropColumn('id_petugas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->string('id_petugas', 20)->nullable();

            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('petugas')
                ->onDelete('set null');
        });
    }
};
