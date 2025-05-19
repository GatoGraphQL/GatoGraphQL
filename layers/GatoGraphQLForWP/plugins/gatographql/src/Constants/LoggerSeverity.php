<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

class LoggerSeverity
{
    public final const ERROR = 'ERROR';
    public final const WARNING = 'WARNING';
    public final const INFO = 'INFO';
    public final const DEBUG = 'DEBUG';

    /**
     * @return string[]
     */
    public final const ALL = [
        self::ERROR,
        self::WARNING,
        self::INFO,
        self::DEBUG,
    ];
}
