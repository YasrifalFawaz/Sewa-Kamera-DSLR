<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Kontrak;

class AdminController extends Controller
{
    // Tambahkan parameter Request $request di dalam fungsi index
    public function index(Request $request)
    {
        // ========================================================
        // LOGIKA PROSES POST (SIMPAN APPROVAL/REJECT KONTRAK)
        // ========================================================
        if ($request->isMethod('post') && $request->has('status')) {
            
            $request->validate([
                'transaksi_id' => 'required',
                'status'       => 'required|in:approved,rejected'
            ]);

            // Cari data kontrak berdasarkan transaksi_id yang dikirim dari form modal
            $kontrak = Kontrak::where('transaksi_id', $request->transaksi_id)->first();

            if ($kontrak) {
                // Update status di tabel kontrak sesuai pilihan admin (approved/rejected)
                $kontrak->update([
                    'status' => $request->status
                ]);
                
                // Refresh halaman dan kirim pesan sukses ke view
                return redirect()->route('admin.dashboard')->with('success', 'Status kontrak berhasil diperbarui!');
            }
        }

        // ========================================================
        // LOGIKA GET (MENAMPILKAN DATA DASHBOARD SEPERTI BIASA)
        // ========================================================
        $kontraks = Kontrak::latest()->get();
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
            'kontraks'     => $kontraks,
        ]);
    }
}