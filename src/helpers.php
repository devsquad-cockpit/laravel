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
