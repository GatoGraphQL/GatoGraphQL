<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use GatoGraphQL\GatoGraphQL\Log\SystemLogger as UpstreamSystemLogger;

class SystemLogger extends UpstreamSystemLogger
{
    use LoggerTrait;

    /**
     * Send the error to the response headers,
     * so we can test it
     */
    public function log(string $message): void
    {
        parent::log($message);

        $this->sendCustomHeader($message);
    }
}
