<?php
declare(strict_types=1);

namespace ElliotJReed\HealthCheck\Parsers;

interface Parser
{
    /**
     * @param string $contents
     * @return array
     */
    public function parse(string $contents): array;
}
