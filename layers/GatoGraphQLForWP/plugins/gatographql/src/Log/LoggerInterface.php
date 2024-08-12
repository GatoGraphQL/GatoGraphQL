<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

interface LoggerInterface
{
    public function logError(string $message): void;
    public function logInfo(string $info): void;
}
