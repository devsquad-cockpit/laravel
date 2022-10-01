<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Status
     |--------------------------------------------------------------------------
     |
     | This setting controls the Cockpit status.
     |
     */
    'enabled' => env('COCKPIT_ENABLED', true),

    /*
     |--------------------------------------------------------------------------
     | Domain
     |--------------------------------------------------------------------------
     |
     | This setting refers to the domain (base URL) of the Cockpit.
     |
     */
    'domain' => env('COCKPIT_DOMAIN'),

    /*
     |--------------------------------------------------------------------------
     | Token
     |--------------------------------------------------------------------------
     |
     | This setting refers to the token related to the project where errors will be created.
     |
     */
    'token' => env('COCKPIT_TOKEN'),
];
