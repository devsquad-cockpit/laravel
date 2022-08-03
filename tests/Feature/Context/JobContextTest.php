<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\JobContext;
use Cockpit\Tests\Fixtures\Jobs\HandleUser;
use Cockpit\Tests\Fixtures\Jobs\HandleUserEncrypted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use ReflectionClass;
use RuntimeException;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->loadMigrationsFrom(__DIR__ . '/../../database'));

/**
 * @covers \Cockpit\Context\JobContext::start()
 */
it('should listen to JobExceptionOccurred event', function () {
    $job = new JobContext(app());
    $job->start();

    $events     = $this->app->get('events');
    $reflection = new ReflectionClass($events);

    $property = $reflection->getProperty('listeners');
    $property->setAccessible(true);

    $listeners = $property->getValue($events);

    expect($listeners)
        ->toHaveKey(JobExceptionOccurred::class)
        ->and($listeners[JobExceptionOccurred::class][0][0])->toBeInstanceOf(JobContext::class);
});

/**
 * @covers \Cockpit\Context\JobContext::reset()
 */
it('should clear job property', function () {
    $job = new JobContext(app());

    expect($job->reset())
        ->toBeInstanceOf(JobContext::class);

    $reflection = new ReflectionClass($job);
    $property   = $reflection->getProperty('job');
    $property->setAccessible(true);

    expect($property->getValue($job))
        ->toBeNull();
});

it('should return an empty array if there is no job to be logged', function () {
    $context = new JobContext(app());

    expect($context->getContext())
        ->toBeArray()
        ->toBeEmpty();
});

/**
 * @covers JobContext::resolveObjectFromCommand()
 */
it('should return job context even if its encrypted', function () {
    HandleUserEncrypted::dispatch('This is a simple message');

    $context = app(JobContext::class);

    $payload = json_decode(DB::table('jobs')->first()->payload, true);

    $message = unserialize(decrypt($payload['data']['command']))->log;

    $this->artisan('queue:work', ['--once' => true]);

    $payload = $context->getContext();

    expect($payload)->toBeArray()
        ->and($payload['name'])->toBe(HandleUserEncrypted::class)
        ->and($payload['connection'])->toBe('database')
        ->and($payload['queue'])->toBe('default')
        ->and($payload['data']['log'])->toBe($message);
});

/**
 * @covers JobContext::resolveObjectFromCommand()
 */
it('should throw an exception when if job is unserializable', function () {
    $context = $this->partialMock(JobContext::class, function (MockInterface $mock) {
        $mock->shouldAllowMockingProtectedMethods();

        return $mock->shouldReceive('resolveObjectFromCommand')
            ->with('some string')
            ->andThrow(RuntimeException::class, 'Unable to extract job payload.');
    });

    $this->loadMigrationsFrom(__DIR__ . '/../../database');

    HandleUser::dispatch('This is a simple message');

    $this->artisan('queue:work', ['--once' => true]);

    expect($context->getContext())
        ->toBeArray()
        ->toBeEmpty();
});

/**
 * @covers JobContext::getJobProperties()
 */
it('shouldnt format pushedAt date if it is not present', function () {
    HandleUser::dispatch('This is a simple message');

    $context = app(JobContext::class);

    $this->artisan('queue:work', ['--once' => true]);

    $this->assertArrayNotHasKey('pushedAt', $context->getContext());
});

it('should add chained jobs on the job that failed if exists', function () {
    $context = app(JobContext::class);

    Bus::chain([
        new HandleUser('A simple chain message'),
        new HandleUserEncrypted('A complex encrypted chain message'),
    ])->dispatch();

    $this->artisan('queue:work', ['--once' => true]);

    $payload = $context->getContext();

    expect($payload)
        ->toBeArray()
        ->and($payload['data']['chained'])->toBeArray()
        ->and($payload['data']['chained'][0]['name'])->toBe(HandleUserEncrypted::class)
        ->and($payload['data']['chained'][0]['data']['log'])->toBe('A complex encrypted chain message');
});

it('should return the job context data', function () {
    HandleUser::dispatch('This is a simple message');

    $context = app(JobContext::class);

    $this->artisan('queue:work', ['--once' => true]);

    $payload = $context->getContext();

    expect($payload)->toBeArray()
        ->and($payload['name'])->toBe(HandleUser::class)
        ->and($payload['connection'])->toBe('database')
        ->and($payload['queue'])->toBe('default')
        ->and($payload['data']['log'])->toBe('This is a simple message');
});
