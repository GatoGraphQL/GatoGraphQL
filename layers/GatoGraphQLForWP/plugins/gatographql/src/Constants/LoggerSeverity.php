<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

class LoggerSeverity
{
    public final const INFO = 'INFO';
    public final const SUCCESS = 'SUCCESS';
    public final const WARNING = 'WARNING';
    public final const ERROR = 'ERROR';

    /**
     * @return string[]
     */
    public final const ALL = [
        self::INFO,
        self::SUCCESS,
        self::WARNING,
        self::ERROR,
    ];
}
