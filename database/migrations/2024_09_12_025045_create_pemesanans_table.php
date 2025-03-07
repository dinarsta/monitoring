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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('atas_nama');
            $table->string('nama_design');
            $table->enum('kategori_acara', ['tempat rekreasi', 'event']);
            $table->integer('QTY');
            $table->string('jenis_barang')->nullable();
            $table->date('tgl_pemesanan');
            $table->date('tgl_deadline');
            $table->enum('status', ['on progress', 'done', 'selesai'])->default('on progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
