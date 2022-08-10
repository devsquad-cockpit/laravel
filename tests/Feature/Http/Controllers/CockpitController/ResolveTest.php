<?php

namespace Cockpit\Tests\Feature\Http\Controllers\CockpitController;

use Cockpit\Cockpit;
use Cockpit\Models\Error;
use Cockpit\Tests\InteractsWithCockpitDatabase;
use Mockery\MockInterface;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    Cockpit::auth(fn () => true);

    $this->setMemoryDatabaseForCockpit();

    $this->refreshCockpitDatabase();
    $this->seedCockpitDatabase();
});

it('should mark an issue as resolved and redirect user with a success message', function () {
    $error = Error::inRandomOrder()->first();

    $this->patch(route('cockpit.resolve', $error))
        ->assertRedirect(route('cockpit.show', $error))
        ->assertSessionHas([
            'toast'   => true,
            'type'    => 'success',
            'message' => 'The error has been resolved',
        ]);

    $this->assertDatabaseMissing(Error::class, [
        'id'          => $error->id,
        'resolved_at' => null,
    ]);
});

it('should redirect user with an error message if marking issue as resolved fails', function () {
    $error = Error::inRandomOrder()->first();

    $this->mock(Error::class, function (MockInterface $mock) use ($error) {
        $mock->shouldReceive('resolveRouteBinding')
            ->andReturn($mock)
            ->shouldReceive('getAttribute')
            ->andReturn($error->id)
            ->shouldReceive('markAsResolved')
            ->andReturn(false);
    });

    $this->patch(route('cockpit.resolve', $error))
        ->assertRedirect(route('cockpit.show', $error))
        ->assertSessionHas([
            'toast'   => true,
            'type'    => 'error',
            'message' => 'An error occurs while performing this operation. Please try again',
        ]);
});
