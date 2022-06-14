<?php

namespace Cockpit\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait InteractsWithUUID
{
    public static function bootInteractsWithUUID()
    {
        static::creating(function (Model $model) {
            $model->uuid = Str::uuid();
        });
    }
}
