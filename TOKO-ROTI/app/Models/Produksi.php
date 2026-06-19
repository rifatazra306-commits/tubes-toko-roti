<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $primaryKey = 'id_order';
    public $timestamps = false;

    protected $fillable = [
        'invoice',
        'kode_customer',
        'kode_produk',
        'nama_produk',
        'qty',
        'harga',
        'status',
        'tanggal',
        'provinsi',
        'kota',
        'alamat',
        'kode_pos',
        'terima',
        'tolak',
        'cek'
    ];
}
