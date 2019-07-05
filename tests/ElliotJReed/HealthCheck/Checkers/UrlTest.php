<?php
declare(strict_types=1);

namespace ElliotJReed\Tests\HealthCheck;

use ElliotJReed\HealthCheck\Checkers\Url;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

final class UrlTest extends TestCase
{
    public function testItLogsTwoHundredResponseAsInfoLevel(): void
    {
        $mock = new MockHandler([new Response(200, [])]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $testHandler = new TestHandler();

        (new Url(new Logger('test', [$testHandler]), $client))
            ->check('http://www.example.net/1');

        $logRecord = $testHandler->getRecords()[0];

        $this->assertTrue($testHandler->hasInfo('http://www.example.net/1'));
        $this->assertSame(['httpStatusCode' => 200, 'transferTime' => 0.00], $logRecord['context']);
    }

    public function testItLogsFourHundredAndFourResponseAsErrorLevel(): void
    {
        $mock = new MockHandler([new Response(404, [])]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $testHandler = new TestHandler();

        (new Url(new Logger('test', [$testHandler]), $client))
            ->check('http://www.example.net/1');

        $logRecord = $testHandler->getRecords()[0];

        $this->assertTrue($testHandler->hasError('http://www.example.net/1'));
        $this->assertSame(['httpStatusCode' => 404, 'transferTime' => 0.00], $logRecord['context']);
    }

    public function testItLogsFiveHundredResponseAsCriticalLevel(): void
    {
        $mock = new MockHandler([new Response(500, [])]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $testHandler = new TestHandler();

        (new Url(new Logger('test', [$testHandler]), $client))
            ->check('http://www.example.net/1');

        $logRecord = $testHandler->getRecords()[0];

        $this->assertTrue($testHandler->hasCritical('http://www.example.net/1'));
        $this->assertSame(['httpStatusCode' => 500, 'transferTime' => 0.00], $logRecord['context']);
    }

    public function testItLogsUnknownResponseAsCriticalLevel(): void
    {
        $mock = new MockHandler([new Response(599, [])]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $testHandler = new TestHandler();

        (new Url(new Logger('test', [$testHandler]), $client))
            ->check('http://www.example.net/1');

        $logRecord = $testHandler->getRecords()[0];

        $this->assertTrue($testHandler->hasCritical('http://www.example.net/1'));
        $this->assertSame(['httpStatusCode' => 599, 'transferTime' => 0.00], $logRecord['context']);
    }
}
