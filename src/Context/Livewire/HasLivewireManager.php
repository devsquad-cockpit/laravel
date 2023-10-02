<?php

namespace Cockpit\Context\Livewire;

trait HasLivewireManager
{
    public function getLivewireManager()
    {
        return app()->make("\Livewire\LivewireManager");
    }
}
