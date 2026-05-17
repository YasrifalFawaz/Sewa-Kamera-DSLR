<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamera extends Model
{
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