<?php
namespace ShamiTheWebdeveloper\NYTimes;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use ShamiTheWebdeveloper\NYTimes\Facades\NYTimesFacade;

class NYTimesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('nytimes', function ($app) {
            return new NYTimes();
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
