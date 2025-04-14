<?php

namespace App\Providers;

use App\Extensions\Dusk\ConsoleDuskBrowserManager;
use Carbon\Laravel\ServiceProvider;
use NunoMaduro\LaravelConsoleDusk\Contracts\ManagerContract;

class DuskServiceProvider extends ServiceProvider
{
    /** Register services */
    public function register()
    {
        $this->app->bind(ManagerContract::class, function ($app) {
            return new ConsoleDuskBrowserManager;
        });
    }
}
