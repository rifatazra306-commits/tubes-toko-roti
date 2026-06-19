<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomProduk extends Model
{
    protected $table = 'bom_produk';
    protected $primaryKey = 'kode_bom';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_bom',
        'kode_bk',
        'kode_produk',
        'nama_produk',
        'kebutuhan'
    ];
}
