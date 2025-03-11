<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'pemesanan_id',
        'atas_nama',
        'nama_design',
        'telp',
        'QTY',
        'tgl_pemesanan',
        'tgl_deadline',
        'jenis_barang',
        'kategori_acara', // Pastikan ini ada
        'status',
        'deleted_at',
    ];
}
