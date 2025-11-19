<?php

namespace Cockpit\Context;

use Cockpit\Context\Livewire\SupportV2;
use Cockpit\Context\Livewire\SupportV3;
use Cockpit\Context\Livewire\SupportV4;
use Cockpit\Interfaces\ContextInterface;
use Composer\InstalledVersions;
use Illuminate\Http\Request;

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

        $livewireInformation = match (true) {
            self::isV2() => (new SupportV2($this->request))->information(),
            self::isV3() => (new SupportV3($this->request))->information(),
            default      => (new SupportV4($this->request))->information(),
        };

        return $this->getRequestData() + $livewireInformation;
    }

    public function isRunningLivewire(): bool
    {
        return $this->request->hasHeader('x-livewire') && $this->request->hasHeader('referer');
    }

    public static function version(): string
    {
        if (self::$version !== null) {
            return self::$version;
        }

        return self::$version = InstalledVersions::getPrettyVersion('livewire/livewire');
    }

    public static function isV4(): bool
    {
        return str_starts_with(self::version(), 'v4.');
    }

    public static function isV3(): bool
    {
        return str_starts_with(self::version(), 'v3.');
    }

    public static function isV2(): bool
    {
        return str_starts_with(self::version(), 'v2.');
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
