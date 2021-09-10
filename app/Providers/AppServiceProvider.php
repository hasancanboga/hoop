<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Model::preventLazyLoading(!app()->isProduction());

        if (app()->environment(['production', 'staging'])) {
            URL::forceScheme('https');
        }

        if(app()->isLocal() && file_exists(base_path('_pre_scripts.php'))){
            /** @noinspection PhpIncludeInspection */
            require base_path('_pre_scripts.php');
        }

    }
}
