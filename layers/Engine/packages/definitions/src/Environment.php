<?php

declare(strict_types=1);

namespace PoP\Definitions;

class Environment
{
    public static function disableDefinitions(): bool
    {
        $envValue = getenv('DISABLE_DEFINITIONS');
        return $envValue !== false ?
            strtolower($envValue) === "true" :
            false;
    }
    public static function disableDefinitionPersistence(): bool
    {
        $envValue = getenv('DISABLE_DEFINITION_PERSISTENCE');
        return $envValue !== false ?
            strtolower($envValue) === "true" :
            false;
    }
}
