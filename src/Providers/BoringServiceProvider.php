<?php

namespace Sakydev\Boring\Providers;

use Illuminate\Support\ServiceProvider;

class BoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(boringPath('routes/api.php'));
        $this->loadMigrationsFrom(boringPath('migrations'));
        $this->loadViewsFrom(boringPath('resources/views'), 'boring');
    }
}

