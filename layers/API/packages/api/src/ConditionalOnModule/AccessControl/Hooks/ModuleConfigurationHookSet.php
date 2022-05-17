<?php

declare(strict_types=1);

namespace PoPAPI\API\ConditionalOnModule\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Module as AccessControlModule;
use PoP\AccessControl\ModuleConfiguration as AccessControlModuleConfiguration;
use PoPAPI\API\Module;
use PoPAPI\API\Environment;
use PoP\Root\Module\ModuleConfigurationHelpers;
use PoP\Root\Hooks\AbstractHookSet;

class ModuleConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        /**
         * Do not enable caching when doing a private schema mode
         */
        /** @var AccessControlModuleConfiguration */
        $moduleConfiguration = App::getModule(AccessControlModule::class)->getConfiguration();
        if ($moduleConfiguration->canSchemaBePrivate()) {
            $hookName = ModuleConfigurationHelpers::getHookName(
                Module::class,
                Environment::USE_SCHEMA_DEFINITION_CACHE
            );
            App::addFilter(
                $hookName,
                fn () => false
            );
        }
    }
}
