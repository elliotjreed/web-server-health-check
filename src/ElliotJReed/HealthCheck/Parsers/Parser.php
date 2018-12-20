<?php
declare(strict_types=1);

namespace ElliotJReed\HealthCheck\Parsers;

interface Parser
{
    public function parse(string $contents): array;
}
