<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kamera;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $kameras = Kamera::latest()->get();

        return view('user.dashboard', compact('users', 'kameras'));
    } 
}
