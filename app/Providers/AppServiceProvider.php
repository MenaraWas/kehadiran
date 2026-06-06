<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Kegiatan;
use App\Observers\KegiatanObserver;

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
        Kegiatan::observe(KegiatanObserver::class);
    }
}
