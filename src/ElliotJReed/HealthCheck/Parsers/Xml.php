<?php

declare(strict_types=1);

namespace ElliotJReed\HealthCheck\Parsers;

final class Xml implements Parser
{
    public function parse(string $sitemapXml): array
    {
        $xml = \simplexml_load_string($sitemapXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $sitemapContents = \json_decode(\json_encode($xml), true)['url'];
        $locations = [];
        foreach ($sitemapContents as $url) {
            $locations[] = $url['loc'];
        }

        return $locations;
    }
}
