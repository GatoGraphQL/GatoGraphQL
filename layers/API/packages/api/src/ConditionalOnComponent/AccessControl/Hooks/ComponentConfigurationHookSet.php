<?php

declare(strict_types=1);

namespace PoPAPI\API\ConditionalOnComponent\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Module as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoPAPI\API\Module;
use PoPAPI\API\Environment;
use PoP\Root\Module\ComponentConfigurationHelpers;
use PoP\Root\Hooks\AbstractHookSet;

class ComponentConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        /**
         * Do not enable caching when doing a private schema mode
         */
        /** @var AccessControlComponentConfiguration */
        $componentConfiguration = App::getComponent(AccessControlComponent::class)->getConfiguration();
        if ($componentConfiguration->canSchemaBePrivate()) {
            $hookName = ComponentConfigurationHelpers::getHookName(
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
