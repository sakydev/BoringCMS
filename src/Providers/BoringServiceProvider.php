<?php

namespace Sakydev\Boring\Providers;

use Illuminate\Support\ServiceProvider;

class BoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'boring');
    }
}

