<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\User;
use App\Models\Transaksi;


class AdminController extends Controller
{
    public function index()
    {
        $now = now();

        return view('admin.dashboard', [
            'users'        => User::latest()->get(),
            'kameras'      => Kamera::latest()->get(),
            'transaksis'   => Transaksi::with(['kamera', 'user'])->latest()->get(),
            'totalKamera'  => Kamera::count(),
            'sedangDisewa' => Transaksi::where('tanggal_sewa', '<=', $now)
                                ->where('tanggal_pengembalian', '>=', $now)
                                ->count(),
            'totalUser'    => User::count(),
        ]);
    }
}
