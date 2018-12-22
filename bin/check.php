#!/usr/bin/env php
<?php
declare(strict_types=1);

use ElliotJReed\HealthCheck\Checkers\Url;
use ElliotJReed\HealthCheck\Parsers\Xml;
use ElliotJReed\HealthCheck\Sitemap;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require __DIR__ . '/../vendor/autoload.php';

if (!isset($argv[1]) || $argv[1] === '--help') {
    echo 'Please specify an XML sitemap URL to scan, and additionally `-v` to include successful response codes.' . PHP_EOL;
    exit(1);
}

$logLevel = 300;
if (isset($argv[2]) && \in_array($argv[2], ['--verbose', '-v', '-vv'])) {
    $logLevel = 200;
}

$guzzle = new Client();
$logger = new Logger('Urls');
$logger->pushHandler(new StreamHandler('php://stdout', $logLevel));

(new Sitemap($guzzle, new Xml(), new Url($logger, $guzzle)))->setUrl($argv[1]);
