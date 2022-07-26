<?php

namespace Cockpit\Context;

use Cockpit\Interfaces\ContextInterface;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Exception\InvalidArgumentException;

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
            'request' => [
                'url'    => $this->request->url(),
                'method' => $this->request->method(),
                'curl'   => $this->getCurl(),
            ],
            'headers'      => $this->request->headers->all(),
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
            $value = implode(',', $value);
            $headers .= "\t-H '{$header}: {$value}' \ \r\n";
        }

        return $headers;
    }

    protected function getCurlBody(): string
    {
        $body    = "";
        $allBody = $this->getBody();
        $lastKey = array_key_last($allBody);
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
        return $this->request->except(array_merge(
            ['_token'],
            $this->request->query->all(),
            array_keys($this->getFiles())
        ));
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

    protected function getSession(): ?SessionInterface
    {
        if (!$this->app->runningInConsole()) {
            return $this->request->getSession();
        }

        return null;
    }

    protected function getCookies(): Collection
    {
        return collect($this->request->cookies->all())
            ->except(['XSRF-TOKEN', 'laravel_session']);
    }
}
