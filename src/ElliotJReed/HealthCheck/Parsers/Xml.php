<?php
declare(strict_types=1);

namespace ElliotJReed\HealthCheck\Parsers;

use SimpleXMLElement;

class Xml implements Parser
{
    /**
     * @param string $sitemap The text content of a sitemap
     * @return array
     */
    public function parse(string $sitemap): array
    {
        $xml = simplexml_load_string($sitemap, 'SimpleXMLElement', LIBXML_NOCDATA);
        $sitemapContents = json_decode(json_encode($xml), true)['url'];
        $locations = [];
        foreach ($sitemapContents as $url) {
            $locations[] = $url['loc'];
        }

        return $locations;
    }
}
