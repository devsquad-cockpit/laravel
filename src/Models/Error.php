<?php

namespace Cockpit\Models;

use Carbon\Carbon;
use Cockpit\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $id
 * @property string $type
 * @property string $exception
 * @property string $message
 * @property int $code
 * @property string|null $url
 * @property string $file
 * @property array $trace
 * @property array|null $app
 * @property array|null $user
 * @property array|null $context
 * @property array|null $request
 * @property array|null $command
 * @property array|null $job
 * @property array|null $livewire
 * @property int $occurrences
 * @property int $affected_users
 * @property Carbon $last_occurrence_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read bool $was_resolved
 * @property-read string $occurrence_time
 * @property-read string $occurrence_description
 *
 * @method static Builder unresolved()
 * @method static Builder onLastHour()
 */
class Error extends BaseModel
{
    use HasUuid;

    public const TYPE_WEB = 'web';
    public const TYPE_CLI = 'cli';
    public const TYPE_JOB = 'job';

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
        'context'            => 'collection',
        'command'            => 'collection',
        'livewire'           => 'collection',
        'job'                => 'collection',
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

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeOnLastHour(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            now()->subHour(),
            now(),
        ]);
    }

    public static function averageErrorsPerDay(): int
    {
        return self::selectRaw(
            'sum(occurrences) / (
                (select count(distinct date(last_occurrence_at)) from errors)
            ) as avg'
        )->value('avg') ?? 0;
    }
}
