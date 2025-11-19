<?php

namespace Cockpit\Context\Livewire;

use Illuminate\Http\Request;

class SupportV4
{
    public function __construct(public Request $request)
    {
    }

    public function information(): array
    {
        $json           = json_decode($this->request->getContent(), true);
        $firstComponent = $json['components'][0] ?? [];
        $firstSnapshot  = json_decode($firstComponent['snapshot'] ?? [], true);

        $componentId    = data_get($firstSnapshot, 'memo.id');
        $componentAlias = data_get($firstSnapshot, 'memo.name');

        if ($componentAlias === null) {
            return [];
        }

        $componentClass = $this->resolveComponentClass($componentAlias);

        return [
            'component_class' => $componentClass,
            'component_alias' => $componentAlias,
            'component_id'    => $componentId,
            'data'            => $firstSnapshot['data']     ?? [],
            'updates'         => $firstComponent['updates'] ?? [],
        ];
    }

    private function resolveComponentClass(string $alias): string
    {
        $finder = app('livewire.finder');

        $resolved = $finder->resolveClassComponentClassName($alias);

        if ($resolved) {
            return $this->normalizePath($resolved);
        }

        $resolved = $finder->resolveSingleFileComponentPath($alias);

        if ($resolved) {
            return $this->normalizePath($resolved);
        }

        $resolved = $finder->resolveMultiFileComponentPath($alias);

        if ($resolved) {
            return $this->normalizePath($resolved);
        }

        return '';
    }

    private function normalizePath(string $value): string
    {
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $value);
    }
}
