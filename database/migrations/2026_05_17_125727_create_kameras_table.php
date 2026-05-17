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
    Schema::create('kameras', function (Blueprint $table) {

        $table->id();
        $table->string('nama_kamera');
        // brand kamera
        $table->string('brand');
        // deskripsi
        $table->text('deskripsi');
        // spesifikasi
        $table->text('spesifikasi');
        // kelengkapan
        $table->text('kelengkapan');
        // harga sewa
        $table->integer('harga');
        // stok
        $table->integer('stock');
        // hanya nama file gambar
        $table->string('image');
        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kameras');
    }
};
