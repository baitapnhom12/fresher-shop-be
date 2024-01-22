<?php

namespace App\Providers;

use App\Enums\UserRole;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isUser', function ($user) {
            return $user->role == UserRole::User
                ? Response::allow()
                : Response::deny('You must be a user.')->withStatus(400);
        });

        Gate::define('isAdmin', function ($user) {
            return $user->role == UserRole::Administrator
                ? Response::allow()
                : Response::deny('You must be a administrator.')->withStatus(400);
        });
    }
}
