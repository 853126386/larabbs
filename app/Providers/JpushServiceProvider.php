<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JpushServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //
        $this->app->singleton('');
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //


    }
}
