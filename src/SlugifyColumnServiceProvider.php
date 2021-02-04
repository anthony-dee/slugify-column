<?php

namespace Cryomagma\SlugifyColumn;

use Illuminate\Support\ServiceProvider;

class SlugifyColumnServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('slugify-column.php'),
            ], 'config');

            $this->commands([
                SlugifyColumn::class,
            ]);
        }
    }
}
