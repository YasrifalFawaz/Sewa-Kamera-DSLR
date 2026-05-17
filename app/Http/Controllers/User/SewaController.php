<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SewaController extends Controller
{
    // dashboard user
    public function index()
    {
        $users = User::latest()->get();
        $kameras = Kamera::latest()->get();
        $transaksis = Transaksi::where(
            'user_id',
            Auth::user()->id
        )->latest()->get();

        return view(
            'user.dashboard',
            compact(
                'kameras',
                'users',
                'transaksis'
            )
        );
    }

    // simpan transaksi sewa
    public function store(Request $request)
    {
        $request->validate([
            'kamera_id' => 'required',
            'tanggal_sewa' => 'required|date',
            'tanggal_pengembalian' =>
                'required|date',
            'metode_pembayaran' =>
                'required',
        ]);

        Transaksi::create([
            'user_id' => Auth::user()->id,
            'kamera_id' => $request->kamera_id,
            'tanggal_sewa' =>
                $request->tanggal_sewa,
            'tanggal_pengembalian' =>
                $request->tanggal_pengembalian,
            'metode_pembayaran' =>
                $request->metode_pembayaran,
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with(
                'success',
                'Kamera berhasil disewa'
            );
    }

    public function generateKontrak($id)
    {
        $transaksi = Transaksi::with(['kamera', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('kontrak.template', [
            'transaksi' => $transaksi,
            'user' => $transaksi->user,
            'kamera' => $transaksi->kamera,
        ]);

        return $pdf->download('kontrak-TRX-'.$transaksi->id.'.pdf');
    }
}