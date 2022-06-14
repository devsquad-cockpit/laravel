<?php

namespace Cockpit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Occurrence extends Model
{
    use Concerns\InteractsWithUUID;

    public const TYPE_WEB   = 'web';
    public const TYPE_CLI   = 'cli';
    public const TYPE_QUEUE = 'queue';

    protected $connection = 'cockpit';

    protected $table = 'cockpit_occurrences';

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    protected $fillable = [
        'cockpit_error_uuid',
        'url',
        'type',
        'message',
        'code',
        'file',
        'trace',
    ];

    protected $casts = [
        'trace' => 'array',
    ];

    public function error(): BelongsTo
    {
        return $this->belongsTo(Error::class, 'cockpit_error_uuid', 'uuid');
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
