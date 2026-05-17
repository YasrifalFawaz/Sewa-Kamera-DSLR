<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class crudController extends Controller
{
    /**
     * Menampilkan semua user
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.dashboard', compact('users'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        return view('admin.crud.create');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'alamat' => 'required',
            'no_telp' => 'required',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect()
                ->route('admin.dashboard')
                ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        return view('admin.crud.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
            'no_telp' => 'required',
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'role' => $request->role,
        ]);

        return redirect()
                ->route('admin.dashboard')
                ->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
                ->route('admin.dashboard')
                ->with('success', 'User berhasil dihapus');
    }
}