<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Engine\Cache\CacheUtils;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class SchemaCacheHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CacheUtils::HOOK_SCHEMA_CACHE_KEY_ELEMENTS,
            $this->getSchemaCacheKeyElements(...)
        );
    }

    /**
     * @return array<string,mixed>
     * @param string[] $elements
     */
    public function getSchemaCacheKeyElements(array $elements): array
    {
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if (!$componentModelModuleConfiguration->supportDefiningServicesInTheContainerBasedOnTheContext()) {
            return $elements;
        }

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $elements['nested-mutations-enabled'] = $moduleConfiguration->enableNestedMutations();
        return $elements;
    }
}
