<?php

namespace Cockpit\Tests\Feature\Event;

use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\Notifications\ErrorNotification;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Monolog\Logger;
use Throwable;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

it('should be send webhook notification', function () {
    config()->set('cockpit.notifications', [
        'webhook' => [
            'enabled' => true,
            'to'      => 'https://webhook.site/123',
        ],
    ]);

    Notification::fake();

    generateError();

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        ErrorNotification::class,
        function ($notification, $channels, $notifiable) {
            return $notifiable->routes['webhook'] === 'https://webhook.site/123';
        }
    );
});

it('should not be send webhook notification', function ($enable, $to) {
    config()->set('cockpit.notifications', [
        'webhook' => [
            'enabled' => $enable,
            'to'      => $to,
        ],
    ]);

    Notification::fake();

    generateError();

    Notification::assertTimesSent(0, ErrorNotification::class);
})->with([
    [true, ''],
    [false, 'https://webhook.site/123']
]);

function generateError() : void
{
    try {
        (new MyService())->handle();
    } catch (Throwable $e) {
    }

    $record = [
        'level'   => Logger::ERROR,
        'context' => [
            'exception' => $e,
        ],
    ];

    $errorHandler = app(CockpitErrorHandler::class);
    $errorHandler->write($record);
}
