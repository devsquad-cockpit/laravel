<?php

namespace Cockpit\Tests\Feature\Notifications;

use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
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

it('should be able to send email', function () {
    config()->set('cockpit.notifications', [
        'mail' => [
            'enabled' => true,
            'to'      => ['cockpit@cockpit.com'],
        ],
    ]);

    Notification::fake();

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

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        ErrorNotification::class,
        function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'][0] === 'cockpit@cockpit.com';
        }
    );
});

it('should be able to send an unique email for multiples occurrences', function () {
    config()->set('cockpit.notifications', [
        'mail' => [
            'enabled' => true,
            'to'      => ['cockpit@cockpit.com'],
        ],
    ]);

    Notification::fake();

    for ($i = 0; $i < 3; $i++) {
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

    $this->assertDatabaseCount(Error::class, 1);
    $this->assertDatabaseCount(Occurrence::class, 3);
    Notification::assertTimesSent(1, ErrorNotification::class);
});

it('should not be able to send email if channel is disabled', function () {
    config()->set('cockpit.notifications', [
        'mail' => [
            'enabled' => false,
            'to'      => ['cockpit@cockpit.com'],
        ],
    ]);

    Notification::fake();

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

    Notification::assertTimesSent(0, ErrorNotification::class);
});

it('should not be able to send email if notifiables is empty', function () {
    config()->set('cockpit.notifications', [
        'mail' => [
            'enabled' => true,
            'to'      => '',
        ],
    ]);

    Notification::fake();

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

    Notification::assertTimesSent(0, ErrorNotification::class);
});
