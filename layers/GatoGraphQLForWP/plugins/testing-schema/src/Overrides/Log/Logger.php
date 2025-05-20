<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use PoPSchema\Logger\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Overrides\Logger\Log\Logger as UpstreamLogger;
use PoPSchema\Logger\Log\LoggerSources;
use GatoGraphQL\TestingSchema\Constants\CustomHeaders;

class Logger extends UpstreamLogger
{
    use LoggerTrait;

    /**
     * Send the error to the response headers,
     * so we can test it
     */
    protected function logMessage(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
    ): void {
        parent::logMessage($severity, $message, $loggerSource);

        $this->sendCustomHeader(
            $message,
            $severity === LoggerSeverity::ERROR
                ? CustomHeaders::GATOGRAPHQL_ERRORS
                : CustomHeaders::GATOGRAPHQL_INFO,
        );
    }
}
