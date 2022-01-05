<?php

declare(strict_types=1);

namespace PoP\API\ConditionalOnComponent\AccessControl\Hooks;

use PoP\Root\Managers\ComponentManager;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\API\Component;
use PoP\API\Environment;
use PoP\BasicService\Component\ComponentConfigurationHelpers;
use PoP\BasicService\AbstractHookSet;

class ComponentConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        /**
         * Do not enable caching when doing a private schema mode
         */
        /** @var AccessControlComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(AccessControlComponent::class)->getConfiguration();
        if ($componentConfiguration->canSchemaBePrivate()) {
            $hookName = ComponentConfigurationHelpers::getHookName(
                Component::class,
                Environment::USE_SCHEMA_DEFINITION_CACHE
            );
            $this->getHooksAPI()->addFilter(
                $hookName,
                fn () => false
            );
        }
    }
}
