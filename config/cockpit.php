<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Theme
     |--------------------------------------------------------------------------
     |
     | Here you may specify if you want to use the light or dark theme
     |
     */

    'dark' => env('COCKPIT_DARK', true),

    /*
     |--------------------------------------------------------------------------
     | Editor
     |--------------------------------------------------------------------------
     |
     | Choose your preferred editor to use when clicking any edit button.
     |
     | Supported: "phpstorm", "vscode", "vscode-insiders", "textmate", "emacs",
     |            "sublime", "atom", "nova", "macvim", "idea", "netbeans",
     |            "xdebug"
     |
     */
    'editor' => env('COCKPIT_EDITOR', 'phpstorm'),
];
