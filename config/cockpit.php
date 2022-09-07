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

    /*
     |--------------------------------------------------------------------------
     | Cockpit database configuration
     |--------------------------------------------------------------------------
     |
     | Choose your preferred database driver in order to use cockpit.
     | This configuration will be merged with laravel database configuration.
     |
     | Supported: "mysql", "pgsql", "sqlite", "sqlsrv"
     */
    'database' => [
        'default' => env('COCKPIT_CONNECTION', 'sqlite'),

        'connections' => [
            'mysql' => [
                'driver'         => 'mysql',
                'url'            => env('COCKPIT_DATABASE_URL'),
                'host'           => env('COCKPIT_DB_HOST', '127.0.0.1'),
                'port'           => env('COCKPIT_DB_PORT', '3306'),
                'database'       => env('COCKPIT_DB_DATABASE', 'forge'),
                'username'       => env('COCKPIT_DB_USERNAME', 'forge'),
                'password'       => env('COCKPIT_DB_PASSWORD', ''),
                'unix_socket'    => env('COCKPIT_DB_SOCKET', ''),
                'charset'        => 'utf8mb4',
                'collation'      => 'utf8mb4_unicode_ci',
                'prefix'         => '',
                'prefix_indexes' => true,
                'strict'         => true,
                'engine'         => null,
                'options'        => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],

            'pgsql' => [
                'driver'         => 'pgsql',
                'url'            => env('COCKPIT_DATABASE_URL'),
                'host'           => env('COCKPIT_DB_HOST', '127.0.0.1'),
                'port'           => env('COCKPIT_DB_PORT', '5432'),
                'database'       => env('COCKPIT_DB_DATABASE', 'forge'),
                'username'       => env('COCKPIT_DB_USERNAME', 'forge'),
                'password'       => env('COCKPIT_DB_PASSWORD', ''),
                'charset'        => 'utf8',
                'prefix'         => '',
                'prefix_indexes' => true,
                'search_path'    => 'public',
                'sslmode'        => 'prefer',
            ],

            'sqlite' => [
                'driver'                  => 'sqlite',
                'url'                     => env('COCKPIT_DATABASE_URL'),
                'database'                => env('COCKPIT_DATABASE', database_path('cockpit.sqlite')),
                'prefix'                  => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', false),
            ],

            'sqlsrv' => [
                'driver'         => 'sqlsrv',
                'url'            => env('COCKPIT_DATABASE_URL'),
                'host'           => env('COCKPIT_DB_HOST', 'localhost'),
                'port'           => env('COCKPIT_DB_PORT', '1433'),
                'database'       => env('COCKPIT_DB_DATABASE', 'forge'),
                'username'       => env('COCKPIT_DB_USERNAME', 'forge'),
                'password'       => env('COCKPIT_DB_PASSWORD', ''),
                'charset'        => 'utf8',
                'prefix'         => '',
                'prefix_indexes' => true,
                // 'encrypt' => env('COCKPIT_DB_ENCRYPT', 'yes'),
                // 'trust_server_certificate' => env('COCKPIT_DB_TRUST_SERVER_CERTIFICATE', 'false'),
            ],
        ]
    ],

    /*
     |--------------------------------------------------------------------------
     | Cockpit notification configuration
     |--------------------------------------------------------------------------
     |
     | Choose your preferred notification channels in order to
     | receive alerts about newly registered errors in the cockpit.
     |
     | Supported: "mail", "webhook", "slack", "twilio", "telegram"
     */
    'notifications' => [
        'mail' => [
            'enabled' => env('COCKPIT_MAIL_ENABLED', false),
            'to'      => explode(',', env('COCKPIT_MAIL_TO', '')),
        ],
        'webhook' => [
            'enabled' => env('COCKPIT_WEBHOOK_ENABLED', false),
            'to'      => env('COCKPIT_WEBHOOK_ROUTE', ''),
        ],
        'slack' => [
            'enabled' => env('COCKPIT_SLACK_ENABLED', false),
            'to'      => env('COCKPIT_SLACK_WEBHOOK', ''),
        ],
        'twilio' => [
            'enabled' => env('COCKPIT_TWILIO_ENABLED', false),
            'to'      => env('COCKPIT_TWILIO_PHONE_NUMBER', ''),
        ],
        'telegram' => [
            'enabled' => env('COCKPIT_TELEGRAM_ENABLED', false),
            'to'      => env('COCKPIT_TELEGRAM_CHAT_ID', ''),
            'token'   => env('COCKPIT_TELEGRAM_BOT_TOKEN', ''),
        ],
        'discord' => [
            'enabled' => env('COCKPIT_DISCORD_ENABLED', false),
            'to'      => env('COCKPIT_DISCORD_BOT_CHANNEL', ''),
            'token'   => env('COCKPIT_DISCORD_BOT_TOKEN', '')
        ],
        'teams' => [
            'enabled' => env('COCKPIT_TEAMS_ENABLED', false),
            'to'      => env('COCKPIT_TEAMS_WEBHOOK_URL', ''),
        ],
    ],
];
