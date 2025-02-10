<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Spatie\Backtrace\Backtrace;
use Spatie\Backtrace\Frame;
use Throwable;

class StackTraceContext implements ContextInterface
{
    protected $app;

    protected $throwable;

    public function __construct(Application $app, Throwable $throwable)
    {
        $this->app       = $app;
        $this->throwable = $throwable;
    }

    public function getContext(): array
    {
        $trace = [];

        $backTrace = Backtrace::createForThrowable($this->throwable)
            ->applicationPath($this->app->basePath());

        foreach ($backTrace->frames() as $frame) {
            $trace[] = [
                'file'              => $frame->file,
                'line'              => $frame->lineNumber,
                'function'          => $frame->method,
                'class'             => $frame->class,
                'application_frame' => $frame->applicationFrame,
                'preview'           => $this->resolveFilePreview($frame),
            ];
        }

        return $trace;
    }

    protected function resolveFilePreview(Frame $frame): array
    {
        if (class_exists('Spatie\Backtrace\CodeSnippet')) {
            return (new \Spatie\Backtrace\CodeSnippet())
                ->surroundingLine($frame->lineNumber)
                ->snippetLineCount(20)
                ->get($frame->file);
        }

        if (class_exists('Spatie\Backtrace\CodeSnippets\CodeSnippet')
            && class_exists('Spatie\Backtrace\CodeSnippets\FileSnippetProvider')) {
            $provider = new \Spatie\Backtrace\CodeSnippets\FileSnippetProvider($frame->file);

            return (new \Spatie\Backtrace\CodeSnippets\CodeSnippet())
                ->surroundingLine($frame->lineNumber)
                ->snippetLineCount(20)
                ->get($provider);
        }

        return [];
    }
}
