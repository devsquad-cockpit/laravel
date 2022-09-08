<?php

namespace Cockpit\Models;

use Cockpit\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $error_id
 * @property string $type
 * @property string|null $url
 * @property array $trace
 * @property array|null $app
 * @property array|null $user
 * @property array|null $context
 * @property array|null $request
 * @property array|null $command
 * @property array|null $job
 * @property array|null $livewire
 * @property array|null $debug
 */
class Occurrence extends BaseModel
{
    use HasUuid;

    public const TYPE_WEB = 'web';
    public const TYPE_CLI = 'cli';
    public const TYPE_JOB = 'job';

    protected $guarded = ['id'];

    protected $casts = [
        'trace'       => 'collection',
        'debug'       => 'collection',
        'app'         => 'collection',
        'user'        => 'collection',
        'context'     => 'collection',
        'request'     => 'collection',
        'command'     => 'collection',
        'job'         => 'collection',
        'livewire'    => 'collection',
        'environment' => 'collection',
    ];

    public function error(): BelongsTo
    {
        return $this->belongsTo(Error::class);
    }

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereRelation('error', 'resolved_at', null);
    }

    public function scopeOnLastHour(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [
            now()->subHour(),
            now(),
        ]);
    }

    public function scopeErrorsFromWeb(Builder $query): builder
    {
        return $query->where('type', self::TYPE_WEB);
    }

    public static function averageOccurrencesPerDay(): int
    {
        return self::selectRaw(
            'count(*) / (
                COALESCE(NULLIF((select count(distinct date(created_at)) from occurrences),0), 1)
            ) as avg'
        )->value('avg') ?? 0;
    }
}
