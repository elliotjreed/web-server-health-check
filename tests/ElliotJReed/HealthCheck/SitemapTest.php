<?php

declare(strict_types=1);

namespace ElliotJReed\Tests\HealthCheck;

use ElliotJReed\HealthCheck\Checkers\Url;
use ElliotJReed\HealthCheck\Parsers\Xml;
use ElliotJReed\HealthCheck\Sitemap;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

final class SitemapTest extends TestCase
{
    public function testItReturnsArrayOfUrlsFromXml(): void
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
              <url>
                <loc>https://www.elliotjreed.com/</loc>
              </url>
              <url>
                <loc>https://www.elliotjreed.com/2</loc>
              </url>
            </urlset>';

        $mock = new MockHandler([
            new Response(200, [], $sitemap),
            new Response(200, [], '<p>I am the content for the second HTTP response</p>'),
            new Response(404, [], '<h1>Not found!</h1>')
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $testHandler = new TestHandler();
        $logger = new Logger('test', [$testHandler]);

        (new Sitemap($client, new Xml(), new Url($logger, $client)))->setUrl('https://www.elliotjreed.com/sitemap.xml');

        $logRecords = $testHandler->getRecords();

        $this->assertTrue($testHandler->hasInfo('https://www.elliotjreed.com/'));
        $this->assertSame(['httpStatusCode' => 200, 'transferTime' => 0.00], $logRecords[0]['context']);
        $this->assertTrue($testHandler->hasError('https://www.elliotjreed.com/2'));
        $this->assertSame(['httpStatusCode' => 404, 'transferTime' => 0.00], $logRecords[1]['context']);
    }
}
