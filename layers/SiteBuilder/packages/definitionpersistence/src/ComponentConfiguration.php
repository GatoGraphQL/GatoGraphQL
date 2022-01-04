<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\BasicService\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private string $getDefinitionPersistenceBuildDir = '';

    /**
     * Disable hook, because it is invoked by `export-directive`
     * on its Component's `resolveEnabled` function.
     */
    public function getDefinitionPersistenceBuildDir(): string
    {
        // Define properties
        $envVariable = Environment::DEFINITION_PERSISTENCE_BUILD_DIR;
        $selfProperty = &$this->getDefinitionPersistenceBuildDir;
        $defaultValue = dirname(__DIR__) . '/build';

        // Initialize property from the environment
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
