<?php

namespace Cockpit\Tests\Unit\Context;

use Cockpit\Context\RequestContext;
use Cockpit\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use RuntimeException;
use Symfony\Component\Mime\Exception\InvalidArgumentException;

class RequestContextTest extends TestCase
{
    /** @test */
    public function it_should_retrieve_basic_request_data(): void
    {
        $appSession = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

        $request = Request::create(
            '/update/',
            'PUT',
            [],
            ['app_session' => $appSession],
            [],
            ['HTTP_ACCEPT' => 'application/json']
        );

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertIsArray($context);

        $this->assertSame('http://localhost/update', $context['request']['url']);
        $this->assertSame('PUT', $context['request']['method']);
        $this->assertSame('application/json', $context['headers']['accept'][0]);
        $this->assertSame([], $context['query_string']);
        $this->assertSame([], $context['body']);
        $this->assertSame([], $context['files']);
        $this->assertInstanceOf(Collection::class, $context['cookies']);
        $this->assertSame($appSession, $context['cookies']['app_session']);
    }

    /** @test */
    public function it_should_test_if_payload_will_comes_with_query_string(): void
    {
        $request = Request::create('/update?only_active=1', 'PUT');

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertSame('http://localhost/update', $context['request']['url']);
        $this->assertSame('PUT', $context['request']['method']);
        $this->assertSame(['only_active' => '1'], $context['query_string']);
    }

    /** @test */
    public function it_should_test_if_payload_will_comes_with_body_content(): void
    {
        $request = Request::create('/update', 'PUT');

        $request->merge([
            'name'      => 'John Doe',
            'is_active' => false,
        ]);

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertSame('http://localhost/update', $context['request']['url']);
        $this->assertSame('PUT', $context['request']['method']);
        $this->assertSame([
            'name'      => 'John Doe',
            'is_active' => false,
        ], $context['body']);
    }

    /** @test */
    public function it_should_test_if_files_are_present_on_payload(): void
    {
        $file = UploadedFile::fake()->image('avatar.png');

        $request = Request::create('/update', 'PUT', [], [], [
            'avatar' => $file,
        ]);

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertSame([
            'avatar' => [
                'pathname' => $file->getPathname(),
                'size'     => $file->getSize(),
                'mimeType' => $file->getMimeType(),
            ],
        ], $context['files']);
    }

    /** @test */
    public function it_should_return_an_empty_array_if_files_arent_an_instance_of_UploadedFile(): void
    {
        $request = Request::create('/update', 'PUT', [], [], [
            'avatar' => [],
        ]);

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertSame([
            'avatar' => [],
        ], $context['files']);
    }

    /**
     * @test
     * @dataProvider data
     */
    public function it_should_return_default_file_values_if_exception_occurs(
        string $method,
        string $exception,
        int $size,
        string $mime
    ): void {
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

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertSame([
            'avatar' => [
                'pathname' => '/some/string',
                'size'     => $size,
                'mimeType' => $mime,
            ],
        ], $context['files']);
    }

    private function data()
    {
        return [
            ['getSize', RuntimeException::class, 0, 'image/jpg'],
            ['getMimeType', InvalidArgumentException::class, 1024, 'undefined'],
        ];
    }

    /** @test */
    public function it_should_check_cURL_command(): void
    {
        $appSession = 'eyJpdiI6IkRIQU1CUHhLS3loNlU5VzNsUHZRcnc9PSIsInZhbHVlIjoiRW5zbnI5N0F0eGQ1dGxmV2h6OU9Ddz09IiwibWFjIjoiZWFmMGZiODUwMWQxY2IzNjI5OGUyYTU1NjUwNDUyZDNiZDk4NjY5YTk5OTk5MTUyZjNmNzI3NmE3NWRhNjcxNCIsInRhZyI6IiJ9';

        $request = Request::create(
            '/update/',
            'PUT',
            [],
            ['app_session' => $appSession],
        );

        $request->merge(['name' => 'John Doe', 'is_active' => false]);

        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $headers = "";

        foreach ($request->headers->all() as $header => $value) {
            $value = implode(',', $value);
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

        $this->assertSame(
            <<<SHELL
    curl "http://localhost/update" \
    -X PUT \
{$headers}{$body}
SHELL,
            $context['request']['curl']
        );
    }

    /** @test */
    public function it_should_return_a_empty_session_collection_if_app_is_running_in_console(): void
    {
        $request = Request::create('/users');
        app()->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext(app()))->getContext();

        $this->assertEmpty($context['session']);
    }

    /** @test */
    public function it_should_session_data_if_application_is_not_running_on_console(): void
    {
        $sessionManager = session();

        $request = Request::create('/users', 'GET', [], [
            'laravel_session' => 'FGdhGpSb6w0c7txC',
        ]);

        $request->setLaravelSession(
            tap($sessionManager->driver(), function ($session) {
                return $session->setId('FGdhGpSb6w0c7txC');
            })
        );

        $sessionManager->start();

        session(['key' => 'data']);

        $app = $this->partialMock(Application::class, function (MockInterface $mock) {
            $mock->shouldReceive('runningInConsole')->andReturn(false);
        });

        $app->bind(Request::class, function () use ($request) {
            return $request;
        });

        $context = (new RequestContext($app))->getContext();

        $this->assertSame(['key' => 'data'], $context['session']->toArray());
    }
}
