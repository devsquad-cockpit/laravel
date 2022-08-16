<?php

namespace Cockpit\Tests\Feature\Event;

use Throwable;
use Monolog\Logger;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Exceptions\CockpitErrorHandler;
use Illuminate\Support\Facades\Notification;
use Cockpit\Notifications\ErrorNotification;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Notifications\AnonymousNotifiable;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

it('should be able to send email', function () {

    config()->set('cockpit.notifications', [
        'email' => [
            'COCKPIT_EMAIL_ENABLED' => true,
            'COCKPIT_TO_EMAIL'      => [
                'cockpit@cockpit.com'
            ],
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
        'email' => [
            'COCKPIT_EMAIL_ENABLED' => true,
            'COCKPIT_TO_EMAIL'      => [
                'cockpit@cockpit.com'
            ],
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
        'email' => [
            'COCKPIT_EMAIL_ENABLED' => false,
            'COCKPIT_TO_EMAIL'      => [
                'cockpit@cockpit.com'
            ],
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
        'email' => [
            'COCKPIT_EMAIL_ENABLED' => true,
            'COCKPIT_TO_EMAIL'      => [],
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
