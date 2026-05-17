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
    Schema::create('transaksis', function (Blueprint $table) {

        $table->id();

        // customer (relasi ke users)
        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');

        // kamera yang disewa
        $table->foreignId('kamera_id')
              ->constrained()
              ->onDelete('cascade');

        // tanggal sewa
        $table->date('tanggal_sewa');

        // tanggal pengembalian
        $table->date('tanggal_pengembalian');

        // metode pembayaran
        $table->enum('metode_pembayaran', [
            'gopay',
            'cash',
            'debit',
            'qris',
            'ovo',
        ]);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
