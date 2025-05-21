<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use PoPSchema\Logger\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Overrides\Logger\Log\Logger as UpstreamLogger;
use GatoGraphQL\TestingSchema\Constants\CustomHeaders;

class Logger extends UpstreamLogger
{
    use LoggerTrait;

    /**
     * Send the error to the response headers,
     * so we can test it.
     *
     * @param array<string,mixed>|null $context
     */
    protected function logMessage(
        string $logFile,
        string $message,
        string $severity,
        ?array $context = null,
    ): void {
        parent::logMessage($logFile, $message, $severity, $context);

        $this->sendCustomHeader(
            $message,
            $severity === LoggerSeverity::ERROR
                ? CustomHeaders::GATOGRAPHQL_ERRORS
                : CustomHeaders::GATOGRAPHQL_INFO,
        );
    }
}
