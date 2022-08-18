<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

class ApplicationState
{
    /**
     * @var array<string,mixed>
     */
    public static array $vars = [];

    /**
     * @return array<string,mixed>
     */
    public static function getVars(): array
    {
        // Only initialize the first time. Then, it will call ->resetState() to retrieve new state, no need to create a new instance
        if (self::$vars) {
            return self::$vars;
        }

        self::$vars = [];

        return self::$vars;
    }
}
