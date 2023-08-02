<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-Privilege', function(User $user){
            if($user->role_id === 1){ #admin
                return true;
            }
        });

        Gate::define('admin-supervisors-Privilege', function(User $user){
            if($user->role_id === 1 || $user->role_id === 2){ #admin or supervisor
                return true;
            }
        });

    }
}
