<?php

namespace App\Providers;

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        Gate::before(fn (?User $user) => $user?->hasRole('superadmin') ? true : null);

        // Override Fortify's verification.verify route to allow unauthenticated access.
        // Registering here (before FortifyServiceProvider::boot) ensures this route
        // is matched first, while Fortify's auth-guarded version is never reached.
        Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['web', 'signed', 'throttle:6,1'])
            ->name('verification.verify');
    }
}
