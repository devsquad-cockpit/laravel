<?php

namespace Cockpit;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CockpitApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->gate();

        Cockpit::auth(function ($request) {
            return Gate::check('viewCockpit', [$request->user()]) || app()->environment('local');
        });
    }

    public function register()
    {
        //
    }

    protected function gate()
    {
        Gate::define('viewCockpit', function ($user) {
            return in_array($user->email, [

            ]);
        });
    }
}
