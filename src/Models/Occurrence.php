<?php

namespace Cockpit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Occurrence extends Model
{
    use Concerns\InteractsWithCockpit;

    protected $table = 'cockpit_occurrences';

    protected $fillable = [
        'cockpit_error_uuid',
        'url',
        'type',
        'message',
        'code',
        'file',
        'trace'
    ];

    protected $casts = [
        'trace' => 'array'
    ];

    public function error(): BelongsTo
    {
        return $this->belongsTo(Error::class, 'cockpit_error_uuid', 'uuid');
    }
}
