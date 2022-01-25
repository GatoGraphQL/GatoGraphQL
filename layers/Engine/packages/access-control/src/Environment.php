<?php

declare(strict_types=1);

namespace PoP\AccessControl;

class Environment
{
    public const DISABLE_ACCESS_CONTROL = 'DISABLE_ACCESS_CONTROL';
    public const USE_PRIVATE_SCHEMA_MODE = 'USE_PRIVATE_SCHEMA_MODE';
    public const ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE = 'ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE';

    public static function disableAccessControl(): bool
    {
        return getenv(self::DISABLE_ACCESS_CONTROL) !== false ? strtolower(getenv(self::DISABLE_ACCESS_CONTROL)) === "true" : false;
    }
}
