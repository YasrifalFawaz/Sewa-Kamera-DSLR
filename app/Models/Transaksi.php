<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [

        'user_id',
        'kamera_id',
        'tanggal_sewa',
        'tanggal_pengembalian',
        'metode_pembayaran',

    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke kamera
    public function kamera()
    {
        return $this->belongsTo(Kamera::class);
    }
}