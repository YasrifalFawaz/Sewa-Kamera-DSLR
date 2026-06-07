<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\crudController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\KameraController;
use App\Http\Controllers\User\SewaController;
use App\Http\Controllers\MoneyDetectionController;
use App\Http\Controllers\KontrakController;

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

Route::post('/detect-money', [MoneyDetectionController::class, 'detect'])
    ->name('detect.money');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/kontrak/store', [KontrakController::class, 'store'])
    ->name('kontrak.store');

    Route::get('/kontrak/{id}', [KontrakController::class, 'show'])
        ->name('kontrak.show');

    Route::post('/kontrak/{id}/approve', [KontrakController::class, 'approve'])
        ->name('kontrak.approve');

    Route::post('/kontrak/{id}/reject', [KontrakController::class, 'reject'])
        ->name('kontrak.reject');

    Route::get('/kontrak/{id}/download', [KontrakController::class, 'download'])
        ->name('kontrak.download');
    
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/dashboard', [AdminController::class, 'index']);
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
