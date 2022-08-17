<?php

namespace Cockpit\Tests\Feature\Notifications;

use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Monolog\Logger;
use Throwable;
use Cockpit\Notifications\ErrorSlackNotification;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

it('should be able to send slack', function () {
    config()->set('cockpit.notifications', [
        'slack' => [
            'enabled' => true,
            'to'      => 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX',
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
        ErrorSlackNotification::class,
        function ($notification, $channels, $notifiable) {
            return $notifiable->routes['slack'] === 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';
        }
    );
});


it('should be able to send an unique slack for multiples occurrences', function () {
    config()->set('cockpit.notifications', [
        'slack' => [
            'enabled' => true,
            'to'      => 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX',
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
    Notification::assertTimesSent(1, ErrorSlackNotification::class);
});


it('should not be able to send slack if channel is disabled', function () {
    config()->set('cockpit.notifications', [
        'slack' => [
            'enabled' => false,
            'to'      => 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX',
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

    Notification::assertTimesSent(0, ErrorSlackNotification::class);
});

it('should not be able to send slack if notifiables is empty', function () {
    config()->set('cockpit.notifications', [
        'slack' => [
            'enabled' => false,
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

    Notification::assertTimesSent(0, ErrorSlackNotification::class);
});