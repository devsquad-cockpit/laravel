<?php

namespace Cockpit\Models;

use Cockpit\Traits\HasUuid;

class Error extends BaseModel
{
    use HasUuid;

    const TYPE_WEB   = 'web';
    const TYPE_CLI   = 'cli';
    const TYPE_QUEUE = 'queue';

    protected $guarded = [];

    protected $casts = [
        'trace'              => 'array',
        'occurrences'        => 'integer',
        'affected_users'     => 'integer',
        'last_occurrence_at' => 'timestamp',
        'resolved_at'        => 'timestamp',
    ];

    public function getWasResolvedAttribute(): bool
    {
        return !is_null($this->resolved_at);
    }
}
