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

#### Run the following command to migrate the database:

```bash
php artisan cockpit:migrate
```

#### Add the following lines to your _composer.json_ file:

```json
"scripts": {
    "post-autoload-dump": [
        "@php artisan cockpit:install --assets --force --ansi"
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

## Settings

### Hide user information in the error report

When running in the web, Cockpit will try to retrieve the logged user. All user data, except that which are defined in `$hidden` property on the user model, will be logged on database

In some cases, you'll need to hide some fields, and you can instruct Cockpit to hide the fields that you want.

```php
// CockpitServiceProvider.php
public function register()
{
    \Cockpit\Cockpit::setUserHiddenFields(['email']);
}
```

_At the above example, the user `email` field won't be logged._

### Restrict access to the Cockpit

The package will work normally in a local environment, but if you try to access the Cockpit in a `production` environment, the access will be granted only to logged users and to users in the list below. 

```php
// CockpitServiceProvider.php
protected function gate()
{
    Gate::define('viewCockpit', fn ($user) => in_array($user->email, [
        'email@email.com',
    ]));
}
```

_In the case above the only user with the email `email@email.com` will have access._
