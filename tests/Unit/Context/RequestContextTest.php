<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Cockpit;
use Cockpit\Context\RequestContext;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use RuntimeException;
use Symfony\Component\Mime\Exception\InvalidArgumentException;

afterAll(function () {
    Cockpit::hideFromRequest([]);
    Cockpit::hideFromHeaders([]);
    Cockpit::hideFromCookies([]);
});

it('should retrieve basic request data', function () {
    $appSession = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => $appSession],
        [],
        ['HTTP_ACCEPT' => 'application/json']
    );

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context)
        ->toBeArray()
        ->not->toBeEmpty()
        ->and($context['request']['url'])->toBe('http://localhost/update')
        ->and($context['request']['method'])->toBe('PUT')
        ->and($context['headers']['accept'][0])->toBe('application/json')
        ->and($context['query_string'])->toBeArray()->toBeEmpty()
        ->and($context['body'])->toBeArray()->toBeEmpty()
        ->and($context['files'])->toBeArray()->toBeEmpty()
        ->and($context['cookies'])->toBeInstanceOf(Collection::class)
        ->and($context['cookies']['app_session'])->toBe($appSession);
});

it('should test if payload will comes with query string', function () {
    $request = Request::create('/update?only_active=1', 'PUT');

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context['request']['url'])->toBe('http://localhost/update')
        ->and($context['request']['method'])->toBe('PUT')
        ->and($context['query_string'])->toBe(['only_active' => '1']);
});

it('should test if payload will comes with body content', function () {
    $request = Request::create('/update', 'PUT');

    $request->merge([
        'name'      => 'John Doe',
        'is_active' => false,
    ]);

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context['request']['url'])->toBe('http://localhost/update')
        ->and($context['request']['method'])->toBe('PUT')
        ->and($context['body'])->toBeArray()->toBe([
            'name'      => 'John Doe',
            'is_active' => false,
        ]);
});

it('should test if files are present on payload', function () {
    $file = UploadedFile::fake()->image('avatar.png');

    $request = Request::create('/update', 'PUT', [], [], [
        'avatar' => $file,
    ]);

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context['files'])->toBe([
        'avatar' => [
            'pathname' => $file->getPathname(),
            'size'     => $file->getSize(),
            'mimeType' => $file->getMimeType(),
        ],
    ]);
});

it('should return an empty array if files arent an instance of UploadedFile', function () {
    $request = Request::create('/update', 'PUT', [], [], [
        'avatar' => [],
    ]);

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context['files'])->toBe([
        'avatar' => [],
    ]);
});

it(
    'it should return default file values if exception occurs',
    function (
        $method,
        $exception,
        $size,
        $mime
    ) {
        $file = $this->partialMock(
            UploadedFile::class,
            function (MockInterface $mock) use ($method, $exception, $size, $mime) {
                $mock->shouldReceive('getPathname')->andReturn('/some/string');

                if ($method == 'getSize') {
                    $mock->shouldReceive('getMimeType')->andReturn($mime);
                } else {
                    $mock->shouldReceive('getSize')->andReturn($size);
                }

                $mock->shouldReceive($method)->andThrow($exception);
            }
        );

        $request = Request::create('/update', 'PUT', [], [], [
            'avatar' => $file,
        ]);

        app()->bind(Request::class, fn () => $request);

        $context = (new RequestContext(app()))->getContext();

        expect($context['files'])->toBe([
            'avatar' => [
                'pathname' => '/some/string',
                'size'     => $size,
                'mimeType' => $mime,
            ],
        ]);
    }
)->with([
    ['getSize', RuntimeException::class, 0, 'image/jpg'],
    ['getMimeType', InvalidArgumentException::class, 1024, 'undefined'],
]);

it('should check cURL command', function () {
    $appSession = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

    $request = Request::create(
        '/update/',
        'PUT',
        [],
        ['app_session' => $appSession],
    );

    $request->merge(['name' => 'John Doe', 'is_active' => false]);

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    $headers = "";

    foreach ($request->headers->all() as $header => $value) {
        $value   = implode(',', $value);
        $headers .= "\t-H '{$header}: {$value}' \ \r\n";
    }

    $body    = "";
    $allBody = $request->all();
    $lastKey = array_key_last($allBody);

    foreach ($allBody as $label => $value) {
        $body .= "\t-F '{$label}={$value}'";

        if ($label != $lastKey) {
            $body .= " \ \r\n";
        }
    }

    expect($context['request']['curl'])
        ->toBe(
            <<<SHELL
    curl "http://localhost/update" \
    -X PUT \
{$headers}{$body}
SHELL
        );
});

it('should check cURL command when application is working with json', function () {
    $request = Request::create(
        '/update/',
        'PUT',
        [],
        [],
        [],
        ['CONTENT_TYPE' => 'application/json']
    );

    $request->merge(['name' => 'John Doe', 'is_active' => false]);

    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    $headers = "";

    foreach ($request->headers->all() as $header => $value) {
        $value   = implode(',', $value);
        $headers .= "\t-H '{$header}: {$value}' \ \r\n";
    }

    $body = "\t-d '" . json_encode(['name' => 'John Doe', 'is_active' => false]) . "' \ \r\n";

    expect($context['request']['curl'])
        ->toBe(
            <<<SHELL
    curl "http://localhost/update" \
    -X PUT \
{$headers}{$body}
SHELL
        );
});

it('should return a empty session collection if app is running in console', function () {
    $request = Request::create('/users');
    app()->bind(Request::class, fn () => $request);

    $context = (new RequestContext(app()))->getContext();

    expect($context['session'])->toBeEmpty();
});

it('should session data if application is not running on console', function () {
    $sessionManager = session();

    $request = Request::create('/users', 'GET', [], [
        'laravel_session' => 'FGdhGpSb6w0c7txC',
    ]);

    $request->setLaravelSession(
        tap($sessionManager->driver(), fn ($session) => $session->setId('FGdhGpSb6w0c7txC'))
    );

    $sessionManager->start();

    session(['key' => 'data']);

    $app = $this->partialMock(Application::class, function (MockInterface $mock) {
        $mock->shouldReceive('runningInConsole')->andReturn(false);
    });

    $app->bind(Request::class, fn () => $request);

    $context = (new RequestContext($app))->getContext();

    expect($context['session']->toArray())
        ->toBe(['key' => 'data']);
});

it('should hide sensitive data from request with default values', function () {
    $request = Request::create('/create', 'POST');
    $request->merge([
        'name'     => 'Some user name',
        'email'    => 'user@email.com',
        'password' => 'password',
    ]);

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['body']['password'])
        ->toBe('*****');
});

it('should hide sensitive data from request with new defined values', function () {
    $request = Request::create('/create', 'POST');
    $request->merge([
        'name'    => 'Some user name',
        'email'   => 'user@email.com',
        'api_key' => Str::random(),
    ]);

    Cockpit::hideFromRequest(['api_key']);

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['body']['api_key'])
        ->toBe('*****');
});

it('should hide sensitive data from a multidimensional array', function () {
    $request = Request::create('/create', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json']);

    $request->merge([
        'user' => [
            'name'     => 'Some user name',
            'password' => 'password',
        ],
    ]);

    Cockpit::hideFromRequest(['user.password']);

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['body']['user']['name'])
        ->toBe('Some user name')
        ->and($context['body']['user']['password'])
        ->toBe('*****');
});

it('should hide headers from request with default cockpit values', function () {
    $request = Request::create('/update', 'PUT');

    $request->headers->set('Authorization', 'Bearer ' . Str::random());

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['headers']['authorization'])
        ->toBe(['*****']);
});

it('should hide headers from request with values defined by user', function () {
    $request = Request::create('/update', 'PUT');

    $request->headers->set('X-Client-Id', Str::random());

    Cockpit::hideFromHeaders(['X-Client-Id']);

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['headers']['x-client-id'])
        ->toBe(['*****']);
});

it('should hide coockies from request with values defined by user', function () {
    $request = Request::create('/update', 'PUT');

    $request->cookies->set('X-Client-Id', Str::random());

    Cockpit::hideFromCookies(['X-Client-Id']);

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    expect($context['cookies']->first())
        ->toBe('*****');
});

it('should check if cURL command will hide headers', function () {
    $request = Request::create('/update', 'PUT');

    $request->merge(['name' => 'John Doe', 'is_active' => 0]);
    $request->headers->set('Authorization', 'Basic ZGV2c3F1YWQ6MTIzNDU2');

    app()->bind(Request::class, fn () => $request);

    $context = app(RequestContext::class)->getContext();

    $headers = "";

    foreach ($request->headers->all() as $header => $value) {
        $value = $header == 'authorization'
            ? '*****'
            : implode(',', $value);

        $headers .= "\t-H '{$header}: {$value}' \ \r\n";
    }

    $body    = "";
    $allBody = $request->all();
    $lastKey = array_key_last($allBody);

    foreach ($allBody as $label => $value) {
        $body .= "\t-F '{$label}={$value}'";

        if ($label != $lastKey) {
            $body .= " \ \r\n";
        }
    }

    expect($context['request']['curl'])
        ->toBe(
            <<<SHELL
    curl "http://localhost/update" \
    -X PUT \
{$headers}{$body}
SHELL
        );
});
