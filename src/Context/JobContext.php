<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Queue\Events\JobExceptionOccurred;

class JobContext implements ContextInterface
{
    protected $app;

    /** @var JobExceptionOccurred|null $job */
    protected $job = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function startTrackingQueueEvents()
    {
        $this->app->get('events')->listen(JobExceptionOccurred::class, [$this, 'setJob']);
    }

    public function setJob(JobExceptionOccurred $job): JobContext
    {
        $this->job = $job;

        return $this;
    }

    public function clearJob(): JobContext
    {
        $this->job = null;

        return $this;
    }

    public function getContext(): ?array
    {
        if (!$this->job) {
            return [];
        }

        return [
            'queue' => $this->job->connectionName,
        ];
    }
}
