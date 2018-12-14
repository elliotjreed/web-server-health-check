<?php
declare(strict_types=1);

namespace ElliotJReed\HealthCheck\Checkers;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\TransferStats;
use Psr\Log\LoggerInterface;

class Url
{
    private $client;
    private $logger;

    /**
     * Url constructor.
     * @param LoggerInterface $logger
     * @param ClientInterface $guzzle
     */
    public function __construct(LoggerInterface $logger, ClientInterface $guzzle)
    {
        $this->client = $guzzle;
        $this->logger = $logger;
    }

    /**
     * @param string $url The URL to be checked
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function check(string $url): void
    {
        $this->client->request('GET', $url, [
            'http_errors' => false,
            'verify' => false,
            'on_stats' => function (TransferStats $stats): void {
                $this->checkStatusCode($stats->getResponse()->getStatusCode(), (string) $stats->getEffectiveUri(), $stats->getTransferTime());
            }
        ]);
    }

    /**
     * @param int $statusCode The HTTP status code
     * @param string $url The URL
     * @param float $transferTime The transfer time in seconds
     */
    private function checkStatusCode(int $statusCode, string $url, float $transferTime): void
    {
        if ($statusCode >= 100 && $statusCode < 400) {
            $this->logger->info($url, ['httpStatusCode' => $statusCode, 'transferTime' => $transferTime]);
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            $this->logger->error($url, ['httpStatusCode' => $statusCode, 'transferTime' => $transferTime]);
        } else {
            $this->logger->critical($url, ['httpStatusCode' => $statusCode, 'transferTime' => $transferTime]);
        }
    }
}
