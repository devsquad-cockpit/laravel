# DevSquad Cockpit

Cockpit is a beautiful error tracking package that will help your software team to track and fix errors.

## Laravel Installation

This package is compatible with **Laravel 6+**.

#### Add these lines to the _composer.json_ file in your project root:

```json
"repositories": [
   {
      "type": "composer",
      "url": "https://devsquad.repo.repman.io"
   }
]
```

#### Create the _auth.json_ file with this content in your project root:

```json
{
    "http-basic": {
        "devsquad.repo.repman.io": {
            "username": "1fc2d46ccf0406664c6427da36c26c3bebadd220b86ff7aed078def2ca03ebd6",
            "password": "1fc2d46ccf0406664c6427da36c26c3bebadd220b86ff7aed078def2ca03ebd6"
        }
    }
}
```

#### Now you can install the package:

```bash
composer require devsquad/cockpit
```

#### Run the following command to install the package files:

```bash
php artisan cockpit:install
```

#### Add the following lines to your _composer.json_ file:

```json
"scripts": {
    "post-autoload-dump": [
        "@php artisan cockpit:install --force --ansi"
    ]
}
```

## Reporting unhandled exceptions
You need to add the Cockpit as a log-channel by adding the following config to the channels section in config/logging.php:

```php
'channels' => [
    // ...
    'cockpit' => [
        'driver' => 'cockpit',
    ],
],
```
After that you need to add it to the stack section:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'cockpit'],
    ],
    //...
],
```
