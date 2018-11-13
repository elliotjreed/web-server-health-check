<?php
declare(strict_types=1);

namespace ElliotJReed\Tests\HealthCheck\Parsers;

use ElliotJReed\HealthCheck\Parsers\Xml;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    public function testItReturnsArrayOfUrlsFromXml(): void
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
              <url>
                <loc>http://www.example.net/1</loc>
                <lastmod>2009-09-22</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
              </url>
              <url>
                <loc>http://www.example.net/2</loc>
                <lastmod>2009-09-22</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
              </url>
              <url>
                <loc>http://www.example.net/3</loc>
                <lastmod>2009-09-22</lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
              </url>
            </urlset>';

        $parsed = (new Xml())->parse($sitemap);

        $this->assertSame(['http://www.example.net/1', 'http://www.example.net/2', 'http://www.example.net/3'], $parsed);
    }
}
