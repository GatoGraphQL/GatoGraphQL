<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

use PoP\Root\Managers\ComponentManager;
use PoP\AccessControl\Component;
use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;

trait AccessControlConfigurableMandatoryDirectivesForItemsTrait
{
    /**
     * Indicates if the entry having no schema mode set must also be processed
     * To decide, it gets the default schema mode and checks its the same with
     * the one from this object
     */
    protected function doesSchemaModeProcessNullControlEntry(): bool
    {
        $individualControlSchemaMode = $this->getSchemaMode();
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return
            ($componentConfiguration->usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE) ||
            (!$componentConfiguration->usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PUBLIC_SCHEMA_MODE);
    }

    abstract protected function getSchemaMode(): string;
}
