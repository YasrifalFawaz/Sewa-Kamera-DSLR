<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\crudController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\KameraController;
use App\Http\Controllers\User\SewaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/crud', crudController::class);
    Route::resource('/admin/crud/kamera', KameraController::class);

});

Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');

    // simpan transaksi
    Route::post(
        '/user/sewa',
        [SewaController::class, 'store']
    )->name('sewa.store');

    Route::get('/sewa/{id}/kontrak', [SewaController::class, 'generateKontrak'])
    ->name('sewa.kontrak');

});

require __DIR__.'/auth.php';
