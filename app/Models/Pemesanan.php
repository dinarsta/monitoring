<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'atas_nama',
        'nama_design',
        'kategori_acara',
        'QTY',
        'tgl_pemesanan',
       'tgl_deadline',
        'status',
        'telp',
    ];
}
