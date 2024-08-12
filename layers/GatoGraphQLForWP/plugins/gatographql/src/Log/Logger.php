<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

class Logger implements LoggerInterface
{
    public function logError(string $message): void
    {
        \error_log(sprintf(
            '[Gato GraphQL] %s',
            $message
        ));
    }

    public function logInfo(string $message): void
    {
        \syslog(LOG_INFO, sprintf(
            '[Gato GraphQL] %s',
            $message
        ));
    }
}
