<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\Root\App;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\Engine\Module;
use PoP\Engine\ModuleConfiguration;

class CacheUtils
{
    public final const HOOK_SCHEMA_CACHE_KEY_ELEMENTS = __CLASS__ . ':schema-cache-key-elements';

    /**
     * @return array<string,mixed>
     */
    public static function getSchemaCacheKeyElements(): array
    {
        $schemaCacheKeyElements = [];

        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        if ($componentModelModuleConfiguration->supportDefiningServicesInTheContainerBasedOnTheContext()) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $schemaCacheKeyElements = [
                'namespaced' => App::getState('namespace-types-and-interfaces'),
                'version-constraint' => App::getState('version-constraint'),
                'field-version-constraints' => App::getState('field-version-constraints'),
                'directive-version-constraints' => App::getState('directive-version-constraints'),
                'redundant-root-fields-disabled' => $moduleConfiguration->disableRedundantRootTypeMutationFields(),
            ];
        }

        return (array)App::applyFilters(
            self::HOOK_SCHEMA_CACHE_KEY_ELEMENTS,
            $schemaCacheKeyElements
        );
    }
}
