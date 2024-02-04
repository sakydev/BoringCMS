<?php

namespace Sakydev\Boring\Providers;

use Illuminate\Support\ServiceProvider;
use Sakydev\Boring\Http\Middlewares\WithJsonMiddleware;

class BoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(boringPath('routes/api.php'));
        $this->loadMigrationsFrom(boringPath('migrations'));
        $this->loadViewsFrom(boringPath('resources/views'), 'boring');

        /*$this->app['router']
            ->aliasMiddleware('with.json', WithJsonMiddleware::class);*/
    }
}

