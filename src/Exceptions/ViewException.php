<?php

namespace Spatie\LaravelIgnition\Exceptions;

use Cockpit\Context\Dump\HtmlDumper;
use ErrorException;

class ViewException extends ErrorException
{
    /** @var array<string, mixed> */
    protected array $viewData = [];

    protected string $view = '';

    /** @return array<string, mixed> */
    public function getViewData(): array
    {
        return $this->viewData;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function setViewData(array $data): void
    {
        $this->viewData = $data;
    }

    public function setView(string $path): void
    {
        $this->view = $path;
    }

    protected function dumpViewData(mixed $variable): string
    {
        return (new HtmlDumper())->dumpVariable($variable);
    }

    /** @return array<string, mixed> */
    public function context(): array
    {
        $context = [
            'view' => [
                'view' => $this->view,
            ],
        ];

        $context['view']['data'] = array_map([$this, 'dumpViewData'], $this->viewData);

        return $context;
    }
}
