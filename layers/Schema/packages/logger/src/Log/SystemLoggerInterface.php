<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

interface SystemLoggerInterface
{
    public function log(string $message): void;
}
