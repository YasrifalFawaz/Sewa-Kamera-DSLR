<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KameraController extends Controller
{
    // READ
    public function index()
    {
        $kameras = Kamera::latest()->get();

        return view('admin.dashboard', compact('kameras'));
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.crud.kamera.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'nama_kamera' => 'required',
            'brand' => 'required',
            'deskripsi' => 'required',
            'spesifikasi' => 'required',
            'kelengkapan' => 'required',
            'harga' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'required',
        ]);

        Kamera::create([
            'nama_kamera' => $request->nama_kamera,
            'brand' => $request->brand,
            'deskripsi' => $request->deskripsi,
            'spesifikasi' => $request->spesifikasi,
            'kelengkapan' => $request->kelengkapan,
            'harga' => $request->harga,
            'stock' => $request->stock,
            'image' => $request->file('image')->store('kamera', 'public'),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data kamera berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit(Kamera $kamera)
    {
        return view('admin.crud.kamera.edit', compact('kamera'));
    }

    // UPDATE
    public function update(Request $request, Kamera $kamera)
    {
        $request->validate([
            'nama_kamera' => 'required',
            'brand' => 'required',
            'deskripsi' => 'required',
            'spesifikasi' => 'required',
            'kelengkapan' => 'required',
            'harga' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'required',
        ]);

        $kamera->update([
            'nama_kamera' => $request->nama_kamera,
            'brand' => $request->brand,
            'deskripsi' => $request->deskripsi,
            'spesifikasi' => $request->spesifikasi,
            'kelengkapan' => $request->kelengkapan,
            'harga' => $request->harga,
            'stock' => $request->stock,
            'image' => $request->file('image')->store('kamera', 'public'),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data kamera berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $kamera = Kamera::findOrFail($id);

        // cek apakah kamera sedang disewa
        $sedangDisewa = Transaksi::where('kamera_id', $kamera->id)
            ->whereDate('tanggal_pengembalian', '>=', now())
            ->exists();

        // jika sedang disewa
        if ($sedangDisewa) {
            return redirect()->back()
                ->with('error', 'Kamera sedang disewa dan tidak dapat dihapus.');
        }

        // hapus kamera
        $kamera->delete();

        return redirect()->back()
            ->with('success', 'Kamera berhasil dihapus.');
    }
}