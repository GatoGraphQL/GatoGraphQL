<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\Root\App;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineComponentConfiguration;

class CacheUtils
{
    public final const HOOK_SCHEMA_CACHE_KEY_COMPONENTS = __CLASS__ . ':schema-cache-key-components';

    public static function getSchemaCacheKeyComponents(): array
    {
        /** @var EngineComponentConfiguration */
        $componentConfiguration = App::getComponent(EngineModule::class)->getConfiguration();
        return (array)App::applyFilters(
            self::HOOK_SCHEMA_CACHE_KEY_COMPONENTS,
            [
                'namespaced' => App::getState('namespace-types-and-interfaces'),
                'version-constraint' => App::getState('version-constraint'),
                'field-version-constraints' => App::getState('field-version-constraints'),
                'directive-version-constraints' => App::getState('directive-version-constraints'),
                'redundant-root-fields-disabled' => $componentConfiguration->disableRedundantRootTypeMutationFields(),
            ]
        );
    }
}
