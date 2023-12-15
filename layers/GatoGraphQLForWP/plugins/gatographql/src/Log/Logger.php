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
}
