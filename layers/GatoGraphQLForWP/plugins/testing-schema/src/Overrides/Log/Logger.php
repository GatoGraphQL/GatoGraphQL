<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use GatoGraphQL\GatoGraphQL\Log\Logger as UpstreamLogger;
use GatoGraphQL\TestingSchema\Constants\CustomHeaders;

class Logger extends UpstreamLogger
{
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    public function logSystemError(string $message): void
    {
        parent::logSystemError($message);

        header(sprintf(
            '%s: %s',
            CustomHeaders::GATOGRAPHQL_ERRORS,
            str_replace(PHP_EOL, '\n', $message)
        ));
    }
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    protected function logOwnStream(string $message): void
    {
        parent::logOwnStream($message);

        header(sprintf(
            '%s: %s',
            CustomHeaders::GATOGRAPHQL_INFO,
            str_replace(PHP_EOL, '\n', $message)
        ));
    }
}
