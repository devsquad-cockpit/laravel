<?php

namespace Cockpit\Models;

use Cockpit\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Occurrence extends BaseModel
{
    use HasUuid;

    public const TYPE_WEB = 'web';
    public const TYPE_CLI = 'cli';
    public const TYPE_JOB = 'job';

    protected $guarded = ['id'];

    protected $casts = [
        'trace'    => 'collection',
        'user'     => 'collection',
        'app'      => 'collection',
        'context'  => 'collection',
        'command'  => 'collection',
        'livewire' => 'collection',
        'job'      => 'collection',
    ];

    public function error(): BelongsTo
    {
        return $this->belongsTo(Error::class);
    }
}
