<?php

return [
    'webhook' => [
        'enabled' => env('COCKPIT_WEBHOOK_ENABLED', true),
        'route' => env('COCKPIT_WEBHOOK_ROUTE'),
    ],
];
