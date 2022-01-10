<?php

namespace App\Providers;

use App\Modules\IUserManagement;
use App\Modules\UserManagement;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUserManagement::class, UserManagement::class);
    }
}
