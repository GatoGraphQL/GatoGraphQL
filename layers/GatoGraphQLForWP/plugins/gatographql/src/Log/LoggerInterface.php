<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

interface LoggerInterface
{
    public function log(string $severity, string $message): void;
}
