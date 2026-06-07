<?php

namespace App\Http\Controllers;

use App\Models\Kontrak;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KontrakController extends Controller
{
    public function store(Request $request)
{
    try {

        $kontrak = Kontrak::create([
            'transaksi_id' => $request->transaksi_id,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'data' => $kontrak
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}

    public function approve($id)
    {
        $kontrak = Kontrak::findOrFail($id);

        $kontrak->update([
            'status' => 'approved'
        ]);

        return back();
    }

    public function reject($id)
    {
        $kontrak = Kontrak::findOrFail($id);

        $kontrak->update([
            'status' => 'rejected'
        ]);

        return back();
    }

    public function download($id)
    {
        $kontrak = Kontrak::findOrFail($id);

        if($kontrak->status != 'approved')
        {
            abort(403);
        }

        $pdf = Pdf::loadView(
            'kontrak.template',
            compact('kontrak')
        );

        return $pdf->download(
            'kontrak-'.$kontrak->id.'.pdf'
        );
    }
}