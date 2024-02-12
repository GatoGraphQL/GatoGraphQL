<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Log;

use GatoGraphQL\GatoGraphQL\Log\Logger as UpstreamLogger;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\CustomHeaders;

class Logger extends UpstreamLogger
{
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    public function logError(string $message): void
    {
        parent::logError($message);

        header(sprintf(
            '%s: %s',
            CustomHeaders::GATOGRAPHQL_ERRORS,
            str_replace(PHP_EOL, '\n', $message)
        ));
    }
}
