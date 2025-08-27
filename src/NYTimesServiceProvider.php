<?php
namespace ShamiTheWebdeveloper\NYTimes;

use Illuminate\Support\ServiceProvider;

class NYTimesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('nytimes', function ($app) {
            return new NYTimes(); // your main class
        });
    }

    public function boot()
    {
        //
    }
}
