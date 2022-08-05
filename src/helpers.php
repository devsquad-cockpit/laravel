<?php

if (!function_exists('error_percentage')) {
    function error_percentage($indexA, $indexB)
    {
        if ($indexA === 0 || $indexB === 0) {
            return 0;
        }

        return ($indexA / $indexB) * 100;
    }
}

if (!function_exists('log_is_object')) {
    function log_is_object($log): bool
    {
        $log = json_decode(json_encode($log));

        return gettype($log) == 'object';
    }
}
