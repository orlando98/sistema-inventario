<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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
    public function boot(){
        Schema::defaultStringLength(191);
        // Carbon::setLocale('es');
        Carbon::setUTF8(true);
        // setlocale(LC_TIME, ""); asi funciona en localhost
        setlocale(LC_TIME, "es_EC");
    }
}
