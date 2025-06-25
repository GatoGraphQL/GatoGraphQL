<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

trait LoggerTrait
{
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    protected function sendCustomHeader(string $message, string $headerName): void
    {
        header(sprintf(
            '%s: %s',
            $headerName,
            str_replace(["\r", "\n"], ['\r', '\n'], $message)
        ));
    }
}
