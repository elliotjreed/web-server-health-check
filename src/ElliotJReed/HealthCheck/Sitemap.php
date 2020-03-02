<?php

declare(strict_types=1);

namespace ElliotJReed\HealthCheck;

use ElliotJReed\HealthCheck\Checkers\Url;
use ElliotJReed\HealthCheck\Parsers\Xml;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\TransferStats;

class Sitemap
{
    private Xml $xmlParser;
    private Url $urlChecker;
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient, Xml $xmlParser, Url $urlChecker)
    {
        $this->xmlParser = $xmlParser;
        $this->urlChecker = $urlChecker;
        $this->httpClient = $httpClient;
    }

    public function setUrl(string $url): void
    {
        $this->httpClient->request('GET', $url, [
            'http_errors' => false,
            'verify' => false,
            'on_stats' => function (TransferStats $stats): void {
                if ($stats->hasResponse()) {
                    $response = $stats->getResponse()->getBody();
                    $response->rewind();
                    $locations = $this->xmlParser->parse($response->getContents());
                    foreach ($locations as $location) {
                        $this->urlChecker->check($location);
                    }
                }
            }
        ]);
    }
}
