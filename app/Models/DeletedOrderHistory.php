<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedOrderHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'pemesanan_id',  // ID of the deleted order
        'atas_nama',     // Customer name
        'nama_design',   // Design name
        'QTY',           // Quantity ordered
        'jenis_barang',  // Type of item
        'telp',  // Type of item
        'tgl_pemesanan', // Order date
        'tgl_deadline',  // Deadline date
        'status'         // Status of the deleted order
    ];
}
