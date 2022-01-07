<?php

declare(strict_types=1);

namespace PoP\FunctionFields;

class Environment
{
    public const DISABLE_FUNCTION_FIELDS = 'DISABLE_FUNCTION_FIELDS';

    public static function disableFunctionFields(): bool
    {
        return getenv(self::DISABLE_FUNCTION_FIELDS) !== false ? strtolower(getenv(self::DISABLE_FUNCTION_FIELDS)) == "true" : false;
    }
}
