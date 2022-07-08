<?php

namespace Cockpit;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CockpitApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->gate();

        Cockpit::auth(
            fn ($request) => Gate::check('viewCockpit', [$request->user()]) || app()->isLocal()
        );
    }

    public function register()
    {
        //
    }

    protected function gate()
    {
        Gate::define('viewCockpit', fn ($user) => in_array($user->email, [
            //
        ]));
    }
}
