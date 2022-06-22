<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\Root\App;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;

class CacheUtils
{
    public final const HOOK_SCHEMA_CACHE_KEY_ELEMENTS = __CLASS__ . ':schema-cache-key-elements';

    public static function getSchemaCacheKeyElements(): array
    {
        /** @var EngineModuleConfiguration */
        $moduleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        return (array)App::applyFilters(
            self::HOOK_SCHEMA_CACHE_KEY_ELEMENTS,
            [
                'namespaced' => App::getState('namespace-types-and-interfaces'),
                'version-constraint' => App::getState('version-constraint'),
                'field-version-constraints' => App::getState('field-version-constraints'),
                'directive-version-constraints' => App::getState('directive-version-constraints'),
                'redundant-root-fields-disabled' => $moduleConfiguration->disableRedundantRootTypeMutationFields(),
            ]
        );
    }
}
