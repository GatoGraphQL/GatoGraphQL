<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\Root\Module\AbstractModuleConfiguration;

class ComponentConfiguration extends AbstractModuleConfiguration
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
