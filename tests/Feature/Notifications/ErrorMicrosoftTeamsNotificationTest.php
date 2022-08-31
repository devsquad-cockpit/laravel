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

const WEBHOOK_URL = 'https://ysr6w.webhook.office.com/webhookb2/33b798d4-b0bd-4f8b-82aa-9d7cd6041406@669452f6-6f17-46d1-b836-7f27174e75b3/IncomingWebhook/3bd1071090ac4a23b46d903c0ddad9e0/0d7c354e-2d98-4df3-97d8-c346a71567d8';

it('should be able to send microsoft team notification', function () {
    config()->set('cockpit.notifications', [
        'teams' => [
            'enabled' => true,
            'to'      => WEBHOOK_URL,
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
            return $notifiable->routes['teams'] === WEBHOOK_URL;
        }
    );
});

it('should be able to send an unique microsoft team notification for multiples occurrences', function () {
    config()->set('cockpit.notifications', [
        'teams' => [
            'enabled' => true,
            'to'      => WEBHOOK_URL,
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

it('should not be able to send microsoft team notification if channel is disabled', function () {
    config()->set('cockpit.notifications', [
        'teams' => [
            'enabled' => false,
            'to'      => WEBHOOK_URL,
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

it('should not be able to send microsoft team notification if notifiables is empty', function () {
    config()->set('cockpit.notifications', [
        'teams' => [
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
