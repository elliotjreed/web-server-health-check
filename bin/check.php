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

$logLevel = 400;

$argumentCount = \count($argv);
for ($i = 1; $i < $argumentCount; $i++) {
    if (\filter_var($argv[$i], FILTER_VALIDATE_URL) !== false) {
        $url = $argv[$i];
    } elseif (\in_array($argv[$i], ['--verbose', '-v', '-vv'])) {
        $logLevel = 200;
    } elseif ($argv[$i] === '--help' || $argv[$i] === '-h') {
        exit('Usage: ./check.php http://example.com/sitemap.xml [--verbose]');
    }
}
if (!isset($url)) {
    echo 'Please specify an XML sitemap URL to scan.' . PHP_EOL . 'Usage: ./check.php http://example.com/sitemap.xml [--verbose]' . PHP_EOL;
    exit(1);
}

$guzzle = new Client();
$logger = (new Logger('Urls'))->pushHandler(new StreamHandler('php://stdout', $logLevel));

(new Sitemap($guzzle, new Xml(), new Url($logger, $guzzle)))->setUrl($argv[1]);
