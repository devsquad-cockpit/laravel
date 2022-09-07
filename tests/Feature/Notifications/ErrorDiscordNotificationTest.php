<?php

namespace Cockpit\Tests\Feature\Notifications;

use Cockpit\Channels\DiscordChannel;
use Cockpit\Notifications\ErrorNotification;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});

it('should be able to send discord message', function () {
    config()->set('cockpit.notifications', [
        'discord' => [
            'enabled' => true,
            'to'      => 'discordChannelId',
            'token'   => 'discordToken'
        ],
    ]);

    Notification::fake();

    dispatchError();

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        ErrorNotification::class,
        function ($notification, $channels, $notifiable) {
            return $notifiable->routes[DiscordChannel::class] === 'discordChannelId';
        }
    );
});

it('should not be able to send discord message', function ($enable, $to, $token) {
    config()->set('cockpit.notifications', [
        'discord' => [
            'enabled' => $enable,
            'to'      => $to,
            'token'   => $token
        ],
    ]);

    Notification::fake();

    dispatchError();

    Notification::assertTimesSent(0, ErrorNotification::class);
})->with([
    [false, 'fakeId', 'fakeToken'],
    [true, null, 'fakeToken']
]);
