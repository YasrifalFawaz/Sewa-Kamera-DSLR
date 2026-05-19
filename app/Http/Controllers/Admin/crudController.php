<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CrudController extends Controller
{
    // TAMPILKAN DASHBOARD + DATA USER
    public function index()
    {
        $users = User::latest()->get();
        $kameras = Kamera::latest()->get();
        $transaksis = Transaksi::with(['kamera', 'user'])->latest()->get();
        return view('admin.dashboard', compact('users', 'kameras', 'transaksis'));
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.crud.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'no_telp' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('crud.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit(User $crud)
    {
        return view('admin.crud.edit', [
            'user' => $crud
        ]);
    }

    // UPDATE
    public function update(Request $request, User $crud)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
            'alamat' => 'required',
        ]);

        $crud->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        return redirect()
            ->route('crud.index')
            ->with('success', 'User berhasil diupdate');
    }

    // DELETE
    public function destroy(User $crud)
    {
        $crud->delete();

        return redirect()
            ->route('crud.index')
            ->with('success', 'User berhasil dihapus');
    }
}