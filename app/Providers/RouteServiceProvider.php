<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * Kita akan menggunakan fungsi getHomeRoute() untuk override nilai ini.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Mendapatkan rute tujuan setelah login berdasarkan peran pengguna.
     * Dipanggil oleh AuthenticatedSessionController.
     */
    public static function getHomeRoute()
    {
        // Mendapatkan objek pengguna yang sedang terotentikasi
        $user = Auth::user();

        // Pastikan pengguna memiliki relasi role yang valid sebelum diakses
        $roleName = $user->role->name ?? null;

        switch ($roleName) {
            case 'admin':
                return '/admin/dashboard';
            case 'doctor':
                return '/doctor/dashboard';
            case 'patient':
                return '/app/doctors'; // Arahkan pasien langsung ke pencarian dokter
            default:
                return '/dashboard'; // Rute default jika peran tidak terdefinisi
        }
    }
}
