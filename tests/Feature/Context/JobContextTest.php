<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\JobContext;
use Illuminate\Queue\Events\JobExceptionOccurred;
use ReflectionClass;

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
