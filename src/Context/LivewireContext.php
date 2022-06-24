<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Livewire\LivewireManager;

class LivewireContext implements ContextInterface
{
    protected $app;

    protected $livewireManager;

    public function __construct(Application $app)
    {
        $this->app = $app;

        if ($this->isRunningLivewire()) {
            $this->livewireManager = $this->app->make(LivewireManager::class);
        }
    }

    public function getContext(): ?array
    {
        if ($this->app->runningInConsole() || !$this->isRunningLivewire()) {
            return null;
        }

        return [];
    }

    protected function isRunningLivewire(): bool
    {
        $request = $this->app->make(Request::class);

        return $request->hasHeader('x-livewire') && $request->hasHeader('referer');
    }
}
