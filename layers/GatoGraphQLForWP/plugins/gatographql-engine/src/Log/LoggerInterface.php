<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use Exception;

interface LoggerInterface
{
    public function logError(string $message): void;
    public function logInfo(string $info): void;
    public function getExceptionMessage(Exception $exception): string;
}
