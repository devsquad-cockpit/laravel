<?php

namespace Cockpit\Tests\Feature\Notifications;

use Cockpit\Notifications\ErrorNotification;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

it('should be able to send telegram message', function () {
    config()->set('cockpit.notifications', [
        'telegram' => [
            'enabled' => true,
            'to'      => 'fakeChatId',
            'token'   => 'fakeBotToken'
        ],
    ]);

    Notification::fake();

    dispatchError();

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        ErrorNotification::class,
        function ($notification, $channels, $notifiable) {
            return $notifiable->routes['telegram'] === 'fakeChatId';
        }
    );
});

it('should not be able to send telegram message', function ($enable, $to, $token) {
    config()->set('cockpit.notifications', [
        'telegram' => [
            'enabled' => $enable,
            'to'      => $to,
            'token'   => $token
        ],
    ]);

    Notification::fake();

    dispatchError();

    Notification::assertTimesSent(0, ErrorNotification::class);
})->with([
    [false, 'fakeChatId', 'fakeBotToken'],
    [true, null, 'fakeBotToken']
]);
