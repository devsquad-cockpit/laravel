<?php

namespace Cockpit\Context;

use Cockpit\Context\Livewire\SupportV3;
use Cockpit\Context\Livewire\SupportV4;
use Cockpit\Interfaces\ContextInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LivewireContext implements ContextInterface
{
    private static ?string $version = null;

    public function __construct(public Request $request)
    {
    }

    public function getContext(): array
    {
        if (app()->runningInConsole() && !app()->runningUnitTests() || !$this->isRunningLivewire()) {
            return [];
        }

        self::$version = $this->version();

        $livewireInformation = match (self::$version) {
            'v3'    => (new SupportV3($this->request))->information(),
            default => (new SupportV4($this->request))->information(),
        };

        return $this->getRequestData() + $livewireInformation;
    }

    public function isRunningLivewire(): bool
    {
        return $this->request->hasHeader('x-livewire') && $this->request->hasHeader('referer');
    }

    public function version(): string
    {
        if (app()->has(\Livewire\Mechanisms\ComponentRegistry::class)) {
            return 'v3';
        }

        if (app()->has('livewire.factory')) {
            return 'v4';
        }

        Log::info('Cockpit - Couldn\'t recognize Livewire version');

        return '';
    }

    protected function getRequestData(): array
    {
        $livewireManager = app('\Livewire\LivewireManager');

        return [
            'url'    => $livewireManager->originalUrl(),
            'method' => $livewireManager->originalMethod(),
        ];
    }
}
