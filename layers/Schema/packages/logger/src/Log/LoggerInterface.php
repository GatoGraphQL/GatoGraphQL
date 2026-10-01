<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

use PoPSchema\Logger\Enums\LoggerSeverity;

interface LoggerInterface
{
    /**
     * When `true`, every log entry is prefixed with "[DRY-RUN]", so that
     * queries executed as a dry-run are distinguishable in the logs.
     */
    public function setDryRun(bool $isDryRun): void;

    /**
     * @param array<string,mixed>|null $context
     */
    public function log(
        LoggerSeverity $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
        ?array $context = null,
    ): void;
}
