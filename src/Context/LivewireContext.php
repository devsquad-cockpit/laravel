<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Livewire\LivewireManager;

class LivewireContext implements ContextInterface
{
    protected Application $app;

    protected $livewireManager;

    public function __construct(Application $app)
    {
        $this->app = $app;

        if ($this->isRunningLivewire()) {
            $this->livewireManager = $this->app->make(LivewireManager::class);
        }
    }

    public function getContext(): array
    {
        if ($this->app->runningInConsole() && !app()->runningUnitTests() || !$this->isRunningLivewire()) {
            return [];
        }

        return $this->getRequestData() + $this->getLivewireInformation();
    }

    protected function isRunningLivewire(): bool
    {
        $request = $this->getRequest();

        return $request->hasHeader('x-livewire') && $request->hasHeader('referer');
    }

    protected function getRequestData(): array
    {
        return [
            'url'    => $this->livewireManager->originalUrl(),
            'method' => $this->livewireManager->originalMethod(),
        ];
    }

    protected function getLivewireInformation(): array
    {
        $request = $this->getRequest();

        $componentId    = $request->input('fingerprint.id');
        $componentAlias = $request->input('fingerprint.name');

        if ($componentAlias === null) {
            return [];
        }

        try {
            $componentClass = $this->livewireManager->getClass($componentAlias);
        } catch (Exception $e) {
            $componentClass = null;
        }

        return [
            'component_class' => $componentClass,
            'component_alias' => $componentAlias,
            'component_id'    => $componentId,
            'data'            => $this->resolveData(),
            'updates'         => $this->resolveUpdates(),
        ];
    }

    protected function getRequest(): Request
    {
        return $this->app->make(Request::class);
    }

    protected function resolveData(): array
    {
        $request = $this->getRequest();

        $data     = $request->input('serverMemo.data')     ?? [];
        $dataMeta = $request->input('serverMemo.dataMeta') ?? [];

        foreach ($dataMeta['modelCollections'] ?? [] as $key => $value) {
            $data[$key] = array_merge($data[$key] ?? [], $value);
        }

        foreach ($dataMeta['models'] ?? [] as $key => $value) {
            $data[$key] = array_merge($data[$key] ?? [], $value);
        }

        return $data;
    }

    protected function resolveUpdates(): array
    {
        $updates = $this->getRequest()->input('updates') ?? [];

        return array_map(function (array $update) {
            $update['payload'] = Arr::except($update['payload'] ?? [], ['id']);

            return $update;
        }, $updates);
    }
}
