#!/usr/bin/env php
<?php
declare(strict_types=1);

use ElliotJReed\HealthCheck\Checkers\Url;
use ElliotJReed\HealthCheck\Parsers\Xml;
use ElliotJReed\HealthCheck\Sitemap;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

if (!isset($argv[1])) {
    echo 'Please specify a sitemap URL' . PHP_EOL;
    exit(1);
}

$guzzle = new Client();
$logger = new Logger('Urls');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../log.txt'));
(new Sitemap($guzzle, new Xml(), new Url($logger, $guzzle)))->setUrl($argv[1]);
