<?php

namespace Cockpit\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $connection = 'cockpit';
}
