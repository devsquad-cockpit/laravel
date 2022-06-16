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
        'last_occurrence_at' => 'datetime',
        'resolved_at'        => 'datetime',
    ];

    public function getWasResolvedAttribute(): bool
    {
        return !is_null($this->resolved_at);
    }

    public function getOccurrenceTime(): string
    {
        $value = explode(' ', $this->last_occurrence_at->diffForHumans());

        return array_shift($value);
    }

    public function getOccurrenceDescription(): string
    {
        $value = explode(' ', $this->last_occurrence_at->diffForHumans());

        array_shift($value);

        return implode(' ', $value);
    }

    public function markAsResolved(): bool
    {
        return $this->update(['resolved_at' => now()]);
    }
}
