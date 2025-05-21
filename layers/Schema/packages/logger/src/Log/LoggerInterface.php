<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

interface LoggerInterface
{
    /**
     * @param array<string,mixed>|null $context
     */
    public function log(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
        ?array $context = null,
    ): void;
}
