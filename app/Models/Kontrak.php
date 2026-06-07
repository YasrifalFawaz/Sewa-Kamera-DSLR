<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    protected $fillable = [

        'transaksi_id',
        'nama',
        'no_hp',
        'alamat',
        'status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}