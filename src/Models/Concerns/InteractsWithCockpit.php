<?php

namespace Cockpit\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * InteractsWithCockpit
 *
 * @mixin Model
 */
trait InteractsWithCockpit
{
    protected $connection = 'cockpit';

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    public static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->uuid = Str::uuid();
        });
    }
}
