<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Constants;

class LoggerSeverity
{
    public final const ERROR = 'ERROR';
    public final const WARNING = 'WARNING';
    public final const INFO = 'INFO';
    public final const DEBUG = 'DEBUG';

    /**
     * Important: The order of the severities is important,
     * as it determines the level of the severity.
     *
     * @return string[]
     */
    public final const ALL = [
        self::ERROR,
        self::WARNING,
        self::INFO,
        self::DEBUG,
    ];
}
