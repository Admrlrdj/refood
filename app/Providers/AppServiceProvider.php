<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
// use MongoDB\Laravel\Sanctum\PersonalAccessToken;
use App\Models\PersonalAccessToken;

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
        // 3. Paksa Sanctum untuk memakai koleksi MongoDB, bukan tabel SQL
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}