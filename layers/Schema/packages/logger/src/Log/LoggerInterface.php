<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

interface LoggerInterface
{
    public function log(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
    ): void;
}
