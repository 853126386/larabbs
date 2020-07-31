<?php

namespace App\Providers;

use JPush\Client as JPush;
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
        $this->app->singleton(JPush::class,function ($app){
            return new JPush(config('jpush.app_key'),config('jpush.master_secret'));
        });
        $this->app->alias(JPush::class,'jpush');

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
