<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConfigurationEntries;

use PoP\Root\App;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return
            ($moduleConfiguration->usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE) ||
            (!$moduleConfiguration->usePrivateSchemaMode() && $individualControlSchemaMode == SchemaModes::PUBLIC_SCHEMA_MODE);
    }

    abstract protected function getSchemaMode(): string;
}
