<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use GatoGraphQL\GatoGraphQL\PluginEnvironment;

class Logger implements LoggerInterface
{
    public function logError(string $message): void
    {
        \error_log(sprintf(
            '[Gato GraphQL] %s',
            $message
        ));
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     */
    public function logInfo(string $message): void
    {
        \error_log(sprintf(
            '[Gato GraphQL] %s',
            $message
        ), 3, PluginEnvironment::getLogsFilePath('logs.txt'));
    }
}
