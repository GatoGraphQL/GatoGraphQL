<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

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
        return
            (ComponentConfiguration::usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE) ||
            (!ComponentConfiguration::usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PUBLIC_SCHEMA_MODE);
    }

    abstract protected function getSchemaMode(): string;
}
