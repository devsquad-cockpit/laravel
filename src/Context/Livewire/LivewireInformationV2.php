<?php

namespace Cockpit\Context\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class LivewireInformationV2
{
    use HasRequestAccess;
    use HasLivewireManager;

    public function information(): array
    {
        $request        = $this->getRequest();
        $componentId    = $request->input('fingerprint.id');
        $componentAlias = $request->input('fingerprint.name');

        if ($componentAlias === null) {
            return [];
        }

        try {
            $componentClass = $this->getLivewireManager()->getClass($componentAlias);
        } catch (Throwable $throwable) {
            $componentClass = null;
            Log::info('Cockpit - Couldn\'t get livewire class:', (array)$throwable);
        }

        return [
            'component_class' => $componentClass,
            'component_alias' => $componentAlias,
            'component_id'    => $componentId,
            'data'            => $this->resolveData(),
            'updates'         => $this->resolveUpdates(),
        ];
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
