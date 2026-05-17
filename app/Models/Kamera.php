<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamera extends Model
{

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
    
    protected $fillable = [

        'nama_kamera',
        'brand',
        'deskripsi',
        'spesifikasi',
        'kelengkapan',
        'harga',
        'stock',
        'image',

    ];
}