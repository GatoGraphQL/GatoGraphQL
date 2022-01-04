<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static string $getDefinitionPersistenceBuildDir = '';

    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public static function getDefinitionPersistenceBuildDir(): string
    {
        // Define properties
        $envVariable = Environment::DEFINITION_PERSISTENCE_BUILD_DIR;
        $selfProperty = &self::$getDefinitionPersistenceBuildDir;
        $defaultValue = dirname(__DIR__) . '/build';

        // Initialize property from the environment
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
