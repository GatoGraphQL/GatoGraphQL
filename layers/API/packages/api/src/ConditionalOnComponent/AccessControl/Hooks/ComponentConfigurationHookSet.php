<?php

declare(strict_types=1);

namespace PoP\API\ConditionalOnComponent\AccessControl\Hooks;

use PoP\API\Environment;
use PoP\API\ComponentConfiguration;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;

class ComponentConfigurationHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        /**
         * Do not enable caching when doing a private schema mode
         */
        if (AccessControlComponentConfiguration::canSchemaBePrivate()) {
            $hookName = ComponentConfigurationHelpers::getHookName(
                ComponentConfiguration::class,
                Environment::USE_SCHEMA_DEFINITION_CACHE
            );
            $this->hooksAPI->addFilter(
                $hookName,
                fn () => false
            );
        }
    }
}
