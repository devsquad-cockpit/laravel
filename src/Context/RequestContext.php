<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Exception\InvalidArgumentException;

class RequestContext implements ContextInterface
{
    protected Application $app;

    protected Request $request;

    protected array $hideFromRequest;

    protected array $hideFromHeaders;

    protected array $hideFromCookies;

    public function __construct(
        Application $app,
        array $hideFromRequest = [],
        array $hideFromHeaders = [],
        array $hideFromCookies = []
    ) {
        $this->app     = $app;
        $this->request = $this->app->make(Request::class);

        $this->hideFromRequest = $hideFromRequest;
        $this->hideFromHeaders = $hideFromHeaders;
        $this->hideFromCookies = $hideFromCookies;
    }

    public function getContext(): ?array
    {
        return [
            'request' => [
                'url'    => $this->request->url(),
                'method' => $this->request->method(),
                'curl'   => $this->getCurl(),
            ],
            'headers'      => $this->getHeaders(),
            'query_string' => $this->request->query->all(),
            'body'         => $this->getBody(),
            'files'        => $this->getFiles(),
            'session'      => $this->getSession(),
            'cookies'      => $this->getCookies(),
        ];
    }

    protected function getCurl(): string
    {
        return <<<SHELL
    curl "{$this->request->url()}" \
    -X {$this->request->method()} \
{$this->getCurlHeaders()}{$this->getCurlBody()}
SHELL;
    }

    protected function getCurlHeaders(): string
    {
        $headers    = "";
        $allHeaders = $this->request->headers->all();

        foreach ($allHeaders as $header => $value) {
            $value = $this->shouldHideHeader($header)
                ? '*****'
                : implode(',', $value);

            $headers .= "\t-H '{$header}: {$value}' \ \r\n";
        }

        return $headers;
    }

    protected function getCurlBody(): string
    {
        $body    = "";
        $allBody = $this->getBody();
        $lastKey = array_key_last($allBody);

        if ($this->request->headers->contains('content-type', 'application/json')) {
            return "\t-d '" . json_encode($allBody) . "' \ \r\n";
        }

        foreach ($allBody as $label => $value) {
            $body .= "\t-F '{$label}={$value}'";

            if ($label != $lastKey) {
                $body .= " \ \r\n";
            }
        }

        return $body;
    }

    protected function getBody(): array
    {
        $data = $this->request->except(
            array_merge(
                ['_token'],
                $this->request->query->all(),
                array_keys($this->getFiles())
            )
        );

        $data = Arr::dot($data);

        foreach (array_keys($data) as $key) {
            if (in_array($key, $this->hideFromRequest)) {
                $data[$key] = '*****';
            }
        }

        return Arr::undot($data);
    }

    protected function getFiles(): array
    {
        if (is_null($this->request->files)) {
            return [];
        }

        return $this->mapFiles($this->request->files->all());
    }

    protected function mapFiles(array $files): array
    {
        return array_map(function ($file) {
            if (is_array($file)) {
                return $this->mapFiles($file);
            }

            if (!$file instanceof UploadedFile) {
                return [];
            }

            try {
                $fileSize = $file->getSize();
            } catch (RuntimeException $e) {
                $fileSize = 0;
            }

            try {
                $mimeType = $file->getMimeType();
            } catch (InvalidArgumentException $e) {
                $mimeType = 'undefined';
            }

            return [
                'pathname' => $file->getPathname(),
                'size'     => $fileSize,
                'mimeType' => $mimeType,
            ];
        }, $files);
    }

    protected function getSession(): Collection
    {
        if (!$this->app->runningInConsole()) {
            return collect($this->request->getSession()->all())
                ->except('_token');
        }

        return collect([]);
    }

    protected function getCookies(): Collection
    {
        $cookies = collect($this->request->cookies->all())
            ->except(['XSRF-TOKEN', config('session.cookie')]);

        foreach (array_keys($cookies->toArray()) as $cookie) {
            if ($this->shouldHideCookie($cookie)) {
                $cookies[$cookie] = '*****';
            }
        }

        return $cookies;
    }

    protected function getHeaders(): array
    {
        $headers = $this->request->headers->all();

        foreach (array_keys($headers) as $header) {
            if ($this->shouldHideHeader($header)) {
                $headers[$header] = ['*****'];
            }
        }

        return $headers;
    }

    protected function shouldHideHeader(string $header): bool
    {
        return in_array(Str::lower($header), $this->hideFromHeaders);
    }

    protected function shouldHideCookie(string $cookie): bool
    {
        return in_array(Str::lower($cookie), $this->hideFromCookies);
    }
}
