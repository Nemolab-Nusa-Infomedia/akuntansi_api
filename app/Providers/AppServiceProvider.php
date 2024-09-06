<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('transfer-owner', function(User $user, $company_id){
            $user_company = $user->user_company;
            if($user_company == null){
                return false;
            }

            return $user_company->where('company_id', $company_id)->count() > 0;
        });
    }
}
