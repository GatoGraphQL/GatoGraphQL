<?php

declare(strict_types=1);

namespace PoP\Definitions;

class Environment
{
    public static function disableDefinitions(): bool
    {
        return getenv('DISABLE_DEFINITIONS') !== false ?
            strtolower(getenv('DISABLE_DEFINITIONS')) === "true" :
            false;
    }
    public static function disableDefinitionPersistence(): bool
    {
        return getenv('DISABLE_DEFINITION_PERSISTENCE') !== false ?
            strtolower(getenv('DISABLE_DEFINITION_PERSISTENCE')) === "true" :
            false;
    }
}
