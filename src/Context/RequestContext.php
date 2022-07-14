<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class RequestContext implements ContextInterface
{
    protected Application $app;
    protected Request     $request;

    public function __construct(Application $app)
    {
        $this->app     = $app;
        $this->request = $this->app->make(Request::class);
    }

    public function getContext(): ?array
    {
        return [
            'url'          => $this->request->fullUrl(),
            'method'       => $this->request->method(),
            'curl'         => $this->getCurl(),
            'headers'      => $this->request->headers->all(),
            'query_string' => $this->request->query->all(),
            'body'         => $this->request->all(),
            'files'        => $this->request->allFiles(),
            'session'      => $this->request->getSession(),
            'cookies'      => $this->request->cookies->all(),
        ];
    }

    protected function getCurl(): string
    {
        return '';
    }
}
