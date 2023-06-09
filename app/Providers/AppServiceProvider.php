<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         //
         if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);

        File::macro('createWithPermissions', function ($path, $permissions) {
            umask(0);
            $handle = fopen($path, 'x');
            fclose($handle);
            chmod($path, $permissions);
        });
    }
    
}
