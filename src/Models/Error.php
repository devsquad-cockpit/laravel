<?php

namespace Cockpit\Models;

use Carbon\Carbon;
use Cockpit\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $id
 * @property string $exception
 * @property string $message
 * @property int $code
 * @property string|null $url
 * @property string $file
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
    use Notifiable;

    protected $guarded = [];

    protected $attributes = [
        'code' => 0,
    ];

    protected $casts = [
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

    public function getUrlAttribute(): string
    {
        return sprintf('%s/cockpit/%s', config('app.url'), $this->id);
    }

    public function markAsResolved(): bool
    {
        return $this->update(['resolved_at' => now()]);
    }

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when(
            $search,
            fn (Builder $query) => $query
                ->where('exception', 'like', "%{$search}%")
                ->orWhere('message', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
        );
    }

    public function scopeBetweenDates(Builder $query, ?string $from, ?string $to)
    {
        return $query->when($from && $to, function (Builder $query) use ($from, $to) {
            $from = Carbon::createFromFormat('y/m/d', $from)->startOfDay();
            $to   = Carbon::createFromFormat('y/m/d', $to)->endOfDay();

            $query->whereBetween('last_occurrence_at', [$from, $to]);
        });
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    public function latestOccurrence(): HasOne
    {
        return $this->hasOne(Occurrence::class)->latestOfMany();
    }
}
