<?php

declare(strict_types=1);

namespace PoPAPI\API\ConditionalOnComponent\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoPAPI\API\Component;
use PoPAPI\API\Environment;
use PoP\Root\Component\ComponentConfigurationHelpers;
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
                Component::class,
                Environment::USE_SCHEMA_DEFINITION_CACHE
            );
            App::addFilter(
                $hookName,
                fn () => false
            );
        }
    }
}
