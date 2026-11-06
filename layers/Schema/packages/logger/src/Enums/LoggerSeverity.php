<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Enums;

/**
 * Important: The order of the cases is important,
 * as it determines the level of the severity.
 */
enum LoggerSeverity: string
{
    case ERROR = 'ERROR';
    case WARNING = 'WARNING';
    case INFO = 'INFO';
    case DEBUG = 'DEBUG';

    public function sign(): string
    {
        return match ($this) {
            self::ERROR => '🔴',
            self::WARNING => '🟡',
            self::INFO => '🔵',
            self::DEBUG => '🟢',
        };
    }
}
