<?php

namespace Cockpit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Error extends Model
{
    const UPDATED_AT = 'last_occurrence';

    protected $connection = 'cockpit';

    protected $table = 'cockpit_errors';

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'exception',
        'resolved_at',
        'occurrences',
        'affected_users',
        'created_at',
        'last_occurrence',
    ];

    protected $casts = [
        'resolved_at'    => 'timestamp',
        'occurrences'    => 'integer',
        'affected_users' => 'integer',
    ];

    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class, 'cockpit_error_uuid', 'uuid');
    }

    public function getWasResolvedAttribute(): bool
    {
        return !is_null($this->resolved_at);
    }
}
