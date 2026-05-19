<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kamera;
use App\Models\User;
use App\Models\Transaksi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('totalKamera', Kamera::count());
        View::share(
            'sedangDisewa',
            Transaksi::whereDate('tanggal_pengembalian', '>=', now())->count()
        );
        View::share('totalUser', User::count());
    }
}
