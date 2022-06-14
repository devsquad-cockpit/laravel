<?php

namespace Cockpit;

class FileContext
{
    public static function getContext(string $filePath, int $line): array
    {
        if (!file_exists($filePath)) {
            return [$line => ''];
        }

        return collect(explode("\n", file_get_contents($filePath)))
            ->slice($line - 20, 30)
            ->mapWithKeys(function ($value, $key) {
                return [$key + 1 => $value];
            })->all();
    }
}
