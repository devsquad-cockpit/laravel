<?php

namespace Cockpit;

use Throwable;

class Cockpit
{
    public static function handle(Throwable $throwable, $fileType = 'php', array $customData = [])
    {
        ray($throwable);
    }
}
