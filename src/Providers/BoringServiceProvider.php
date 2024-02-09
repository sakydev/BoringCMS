<?php

namespace Sakydev\Boring\Providers;

use Illuminate\Support\ServiceProvider;

class BoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(boringPath('config/auth.php'), 'auth');
        $this->loadRoutesFrom(boringPath('routes/api.php'));
        $this->loadMigrationsFrom(boringPath('migrations'));
        $this->loadViewsFrom(boringPath('resources/views'), 'boring');

        $this->loadJsonTranslationsFrom(boringPath('lang'));

        /*$this->app['router']
            ->aliasMiddleware('with.json', WithJsonMiddleware::class);*/
    }
}

