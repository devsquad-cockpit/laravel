<?php

namespace Cockpit\Tests\Feature\Context;

use Throwable;
use Monolog\Logger;
use Cockpit\Models\Error;
use Cockpit\Models\Occurrence;
use Cockpit\Exceptions\CockpitErrorHandler;
use Cockpit\Tests\Fixtures\Services\MyService;
use Cockpit\Tests\InteractsWithCockpitDatabase;

uses(InteractsWithCockpitDatabase::class);

beforeEach(function () {
    $this->setMemoryDatabaseForCockpit();
    $this->refreshCockpitDatabase();
});


it('should be able to create ocurrences', function () {

    try {
        (new MyService())->handle();
    } catch (Throwable $e) {
    }

    $record = [
        'level'   => Logger::ERROR,
        'context' => [
            'exception' => $e,
        ],
    ];

    $errorHandler = app(CockpitErrorHandler::class);
    $errorHandler->write($record);

    $this->assertDatabaseCount(Occurrence::class, 1);
});

it('should be able to create ocurrences with a custom message', function () {

    try {
        (new MyService())->handle();
    } catch (Throwable $e) {
    }

    $record = [
        'level'   => Logger::ERROR,
        'context' => [
            'exception' => $e,
            'message'   => 'My custom message',
        ],
    ];

    $errorHandler = app(CockpitErrorHandler::class);
    $errorHandler->write($record);

    $this->assertDatabaseCount(Occurrence::class, 1);

    $this->assertEquals('My custom message', Occurrence::first()->context->first());
});

it('should be able to create multiples ocurrences for the same error', function () {

    $service = new MyService();

    try {
        $service->handle();
    } catch (Throwable $e) {
    }

    $record = [
        'level'   => Logger::ERROR,
        'context' => [
            'exception' => $e,
        ],
    ];

    $errorHandler = app(CockpitErrorHandler::class);
    $errorHandler->write($record);
    $errorHandler->write($record);

    $this->assertDatabaseCount(Error::class, 1);
    $this->assertDatabaseCount(Occurrence::class, 2);
    $this->assertCount(2, Error::first()->occurrences);
});
