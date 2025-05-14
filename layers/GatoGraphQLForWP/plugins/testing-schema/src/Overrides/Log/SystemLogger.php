<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use GatoGraphQL\GatoGraphQL\Log\SystemLogger as UpstreamSystemLogger;
use GatoGraphQL\TestingSchema\Constants\CustomHeaders;

class SystemLogger extends UpstreamSystemLogger
{
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    public function log(string $message): void
    {
        parent::log($message);

        header(sprintf(
            '%s: %s',
            CustomHeaders::GATOGRAPHQL_ERRORS,
            str_replace(PHP_EOL, '\n', $message)
        ));
    }
}
