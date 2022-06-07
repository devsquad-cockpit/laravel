<?php

return [
    'database' => [
        'connnection' => env('COCKPIT_DB_CONNECTION', 'mysql'),
        'host'        => env('COCKPIT_DB_HOST', '127.0.0.1'),
        'port'        => env('COCKPIT_DB_PORT', '3306'),
        'database'    => env('COCKPIT_DB_DATABASE', 'forge'),
        'username'    => env('COCKPIT_DB_USERNAME', 'forge'),
        'password'    => env('COCKPIT_DB_PASSWORD', ''),
    ],
];
