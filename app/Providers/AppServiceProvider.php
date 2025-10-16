<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

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
        // Define manage-users gate for admin-only access
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Define evaluate-project gate for advisors and committee members
        Gate::define('evaluate-project', function (User $user) {
            return $user->isAdvisor() || $user->isCommittee();
        });

        // Define manage-categories gate for admin-only access
        Gate::define('manage-categories', function (User $user) {
            return $user->isAdmin();
        });
    }
}
