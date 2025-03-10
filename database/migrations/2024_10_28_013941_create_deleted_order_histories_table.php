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
        Schema::create('deleted_order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemesanan_id');
            $table->string('atas_nama');
            $table->string('nama_design');
            $table->string('telp');
            $table->enum('kategori_acara', ['tempat rekreasi', 'event']);
            $table->integer('QTY');
            $table->date('tgl_pemesanan');
            $table->date('tgl_deadline');
            $table->string('jenis_barang');
            $table->string('status');
            $table->timestamp('deleted_at');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_order_histories');
    }
};
