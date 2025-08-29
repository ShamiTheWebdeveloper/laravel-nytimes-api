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
        $this->mergeConfigFrom(
            __DIR__ . '/config/nytimes.php',
            'nytimes'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/nytimes.php' => config_path('nytimes.php'),
        ], 'nytimes-config');
    }
}
