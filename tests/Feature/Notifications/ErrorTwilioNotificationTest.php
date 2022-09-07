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

it('should be able to send twilio sms', function () {
    config()->set('cockpit.notifications', [
        'twilio' => [
            'enabled' => true,
            'to'      => '+1234567890',
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
            return $notifiable->routes['twilio'] === '+1234567890';
        }
    );
});

it('should be able to send an unique twilio sms for multiples occurrences', function () {
    config()->set('cockpit.notifications', [
        'twilio' => [
            'enabled' => true,
            'to'      => '+1234567890',
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

it('should not be able to send twilio sms if channel is disabled', function () {
    config()->set('cockpit.notifications', [
        'twilio' => [
            'enabled' => false,
            'to'      => '+1234567890',
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

it('should not be able to send twilio sms if notifiables is empty', function () {
    config()->set('cockpit.notifications', [
        'twilio' => [
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
