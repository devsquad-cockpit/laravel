<?php

namespace Cockpit\Context;

use Cockpit\Context\Livewire\HasLivewireManager;
use Cockpit\Context\Livewire\HasRequestAccess;
use Cockpit\Context\Livewire\LivewireInformationV2;
use Cockpit\Context\Livewire\LivewireInformationV3;
use Cockpit\Interfaces\ContextInterface;
use Illuminate\Support\Facades\Log;

class LivewireContext implements ContextInterface
{
    use HasRequestAccess;
    use HasLivewireManager;

    public function getContext(): array
    {
        if (app()->runningInConsole() && !app()->runningUnitTests() || !$this->isRunningLivewire()) {
            return [];
        }

        $livewireInformation = match ($this->livewireVersion()) {
            'v2'    => new LivewireInformationV2(),
            'v3'    => new LivewireInformationV3(),
            default => []
        };

        return $this->getRequestData() + $livewireInformation->information();
    }

    public function isRunningLivewire(): bool
    {
        $request = $this->getRequest();

        return $request->hasHeader('x-livewire') && $request->hasHeader('referer');
    }

    public function livewireVersion(): string
    {
        if (class_exists('\Livewire\LivewireComponentsFinder')) {
            return 'v2';
        }

        if (class_exists('\Livewire\Mechanisms\ComponentRegistry')) {
            return 'v3';
        }

        Log::info('Cockpit - Couldn\'t recognize Livewire version');

        return '';
    }

    protected function getRequestData(): array
    {
        return [
            'url'    => $this->getLivewireManager()->originalUrl(),
            'method' => $this->getLivewireManager()->originalMethod(),
        ];
    }
}
