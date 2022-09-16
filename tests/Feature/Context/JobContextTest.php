<?php

namespace Cockpit\Tests\Feature\Context;

use Closure;
use Cockpit\Context\JobContext;
use Exception;
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
    
    if($listeners[JobExceptionOccurred::class][0] instanceof Closure) {
        $listener = $listeners[JobExceptionOccurred::class][0](null, [
            'job'=>new JobExceptionOccurred('queue', 'exception', new Exception())
        ]);
    } else {
        $listener = $listeners[JobExceptionOccurred::class][0][0];
    }

    expect($listeners)
        ->toHaveKey(JobExceptionOccurred::class)
        ->and($listener)->toBeInstanceOf(JobContext::class);
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
