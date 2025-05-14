<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Overrides\Log;

use GatoGraphQL\TestingSchema\Constants\CustomHeaders;

trait LoggerTrait
{
    /**
     * Send the error to the response headers,
     * so we can test it
     */
    protected function sendCustomHeader(string $message): void
    {
        header(sprintf(
            '%s: %s',
            CustomHeaders::GATOGRAPHQL_INFO,
            str_replace(PHP_EOL, '\n', $message)
        ));
    }
}
