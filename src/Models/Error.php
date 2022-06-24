<?php

namespace Cockpit\Models;

use Carbon\Carbon;
use Cockpit\Traits\HasUuid;

/**
 * @property string      $id
 * @property string      $type
 * @property string      $exception
 * @property string      $message
 * @property int         $code
 * @property string|null $url
 * @property string      $file
 * @property array       $trace
 * @property array|null  $app
 * @property array|null  $user
 * @property array|null  $context
 * @property array|null  $request
 * @property array|null  $command
 * @property array|null  $job
 * @property array|null  $livewire
 * @property int         $occurrences
 * @property int         $affected_users
 * @property Carbon      $last_occurrence_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read bool   $was_resolved
 * @property-read string $occurrence_time
 * @property-read string $occurrence_description
 */
class Error extends BaseModel
{
    use HasUuid;

    protected $guarded = [];

    protected $attributes = [
        'code'           => 0,
        'occurrences'    => 0,
        'affected_users' => 0,
    ];

    protected $casts = [
        'trace'              => 'collection',
        'user'               => 'collection',
        'app'                => 'collection',
        'command'            => 'collection',
        'occurrences'        => 'integer',
        'affected_users'     => 'integer',
        'last_occurrence_at' => 'datetime',
        'resolved_at'        => 'datetime',
    ];

    public function getWasResolvedAttribute(): bool
    {
        return !is_null($this->resolved_at);
    }

    public function getOccurrenceTimeAttribute(): string
    {
        $value = explode(' ', $this->last_occurrence_at->diffForHumans());

        return array_shift($value);
    }

    public function getOccurrenceDescriptionAttribute(): string
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
