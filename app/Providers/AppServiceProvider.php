<?php

namespace App\Providers;

use App\Models\{Ticket, User};
use App\Policies\TicketPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Gate, RateLimiter};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Pertahankan logika register() bawaan jika ada
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Registrasi Policy secara eksplisit
        Gate::policy(Ticket::class, TicketPolicy::class);

        // 2. Gate otorisasi untuk laporan summary admin
        Gate::define('view-ticket-summary', fn (User $user) => (bool) $user->is_admin);

        // 3. Rate Limiter khusus endpoint Login (Maksimal 5 request / menit per IP)
        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by('login-ip:'.$request->ip());
        });

        // 4. Rate Limiter umum API v1 (Maksimal 60 request / menit per User ID)
        RateLimiter::for('api-v1', function (Request $request) {
            return Limit::perMinute(60)->by('api-user:'.$request->user()->id);
        });
    }
}