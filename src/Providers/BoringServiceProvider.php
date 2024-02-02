<?php

namespace Sakydev\Boring\Providers;

use Illuminate\Support\ServiceProvider;

class BoringServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    }
}

