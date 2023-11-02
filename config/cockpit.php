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
     | This setting refers to the token related with the project
     | in the Cockpit where the errors will be registered.
     |
     */
    'token' => env('COCKPIT_TOKEN'),

    /*
     |--------------------------------------------------------------------------
     | Before Send
     |--------------------------------------------------------------------------
     |
     | All exceptions will be called back for handling before being sent.
     |
     */
    'before_send' => function($throwable, array $context = []) {

        return [$throwable, $context];
    }
];
