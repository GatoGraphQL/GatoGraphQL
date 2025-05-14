<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

interface SystemLoggerInterface
{
    public function log(string $message): void;
}
