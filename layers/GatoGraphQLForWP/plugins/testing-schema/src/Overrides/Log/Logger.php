<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use GatoGraphQL\GatoGraphQL\Log\Logger as UpstreamLogger;

class Logger extends UpstreamLogger
{
    use LoggerTrait;

    /**
     * Send the error to the response headers,
     * so we can test it
     */
    protected function logMessage(string $severity, string $message): void
    {
        parent::logMessage($severity, $message);

        $this->sendCustomHeader($message);
    }
}
