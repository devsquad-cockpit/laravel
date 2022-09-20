<?php

namespace Cockpit\Tests\Feature\Context;

use Cockpit\Context\JobContext;
use Cockpit\Tests\TestCase;
use Illuminate\Queue\Events\JobExceptionOccurred;
use ReflectionClass;

class JobContextTest extends TestCase
{
    /**
     * @test
     * @covers \Cockpit\Context\JobContext::start()
     */
    public function it_should_listen_to_JobExceptionOccurred_event(): void
    {
        $job = new JobContext(app());
        $job->start();

        $events     = $this->app->get('events');
        $reflection = new ReflectionClass($events);

        $property = $reflection->getProperty('listeners');
        $property->setAccessible(true);

        $listeners = $property->getValue($events);
        $listener  = $listeners[JobExceptionOccurred::class][0][0];

        $this->assertArrayHasKey(JobExceptionOccurred::class, $listeners);
        $this->assertInstanceOf(JobContext::class, $listener);
    }

    /**
     * @test
     * \Cockpit\Context\JobContext::reset()
     */
    public function it_should_clear_job_property(): void
    {
        $job = new JobContext(app());

        $this->assertInstanceOf(JobContext::class, $job->reset());

        $reflection = new ReflectionClass($job);
        $property   = $reflection->getProperty('job');
        $property->setAccessible(true);

        $this->assertNull($property->getValue($job));
    }

    /** @test */
    public function it_should_return_an_empty_array_if_there_is_no_job_to_be_logged(): void
    {
        $this->assertSame([], (new JobContext(app()))->getContext());
    }
}
