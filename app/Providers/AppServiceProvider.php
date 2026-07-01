<?php

namespace App\Providers;

use App\Listeners\AssignFreePlan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        if ($this->app->has('request') && $this->app['request']->isSecure()) {
            URL::forceScheme('https');
        }

        Event::listen(Registered::class, AssignFreePlan::class);
    }
}
