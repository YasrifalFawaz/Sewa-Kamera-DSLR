<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;

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
        // 1. Ambil data kamera & validasi input
        $kamera = Kamera::findOrFail($request->kamera_id);

        // Cek stok kamera
        if ($kamera->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok kamera habis, tidak dapat disewa.'
            ], 422);
        }

        $request->validate([
            'kamera_id' => 'required|exists:kameras,id',
            'tanggal_sewa' => 'required|date',
            'tanggal_pengembalian' => 'required|date',
            'metode_pembayaran' => 'required',
        ]);

        // 2. Kalkulasi Durasi Sewa dan Total Harga
        $tglSewa = Carbon::parse($request->tanggal_sewa);
        $tglKembali = Carbon::parse($request->tanggal_pengembalian);
        $durasiHari = $tglSewa->diffInDays($tglKembali);
        
        if ($durasiHari <= 0) {
            $durasiHari = 1; // Minimal sewa dihitung 1 hari jika tanggalnya sama
        }
        
        $totalHarga = $kamera->harga * $durasiHari;

        // 3. JIKA METODE PEMBAYARAN CASH
        if ($request->metode_pembayaran === 'cash') {
            Transaksi::create([
                'user_id' => Auth::user()->id,
                'kamera_id' => $request->kamera_id,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'metode_pembayaran' => 'cash',
                // Tambahkan kolom total_harga atau status jika skema tabel Anda memilikinya, misalnya:
                // 'total_harga' => $totalHarga,
                // 'status_pembayaran' => 'pending'
            ]);

            // Kurangi stok kamera
            $kamera->stock -= 1;
            $kamera->save();

            return response()->json([
                'success' => true,
                'message' => 'Kamera berhasil disewa secara Cash!'
            ]);
        }

        // 4. JIKA METODE PEMBAYARAN ONLINE (MIDTRANS)
        // Konfigurasi Midtrans Environment
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false; // Ubah ke true jika sudah siap produksi (Live)
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Generate Order ID Unik
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);

        // Siapkan data transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalHarga, 
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => $kamera->id,
                    'price' => (int) $kamera->harga,
                    'quantity' => $durasiHari,
                    'name' => substr($kamera->nama_kamera, 0, 40) . ' (' . $durasiHari . ' Hari)'
                ]
            ]
        ];

        try {
            // Request Token Ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan Transaksi ke database dengan status belum dibayar
            Transaksi::create([
                'user_id' => Auth::user()->id,
                'kamera_id' => $request->kamera_id,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'metode_pembayaran' => $request->metode_pembayaran,
                // Opsional: Jika tabel Anda punya kolom-kolom ini, silakan di-uncomment
                // 'id_transaksi' => $orderId,
                // 'total_harga' => $totalHarga,
                // 'status_pembayaran' => 'pending'
            ]);

            // Kurangi stok kamera (Stok langsung dikunci begitu pop-up muncul)
            $kamera->stock -= 1;
            $kamera->save();

            // Kembalikan token ke javascript blade
            return response()->json([
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()
            ], 500);
        }
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