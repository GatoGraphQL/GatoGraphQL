<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\BasicService\Component\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    public function getDefinitionPersistenceBuildDir(): string
    {
        $envVariable = Environment::DEFINITION_PERSISTENCE_BUILD_DIR;
        $defaultValue = dirname(__DIR__) . '/build';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
