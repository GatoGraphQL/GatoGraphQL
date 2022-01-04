<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Hooks\Facades\HooksAPIFacade;

class CacheUtils
{
    public const HOOK_SCHEMA_CACHE_KEY_COMPONENTS = __CLASS__ . ':schema-cache-key-components';

    public static function getSchemaCacheKeyComponents(): array
    {
        $vars = ApplicationState::getVars();
        $hooksAPI = HooksAPIFacade::getInstance();
        return (array)$hooksAPI->applyFilters(
            self::HOOK_SCHEMA_CACHE_KEY_COMPONENTS,
            [
                'namespaced' => $vars['namespace-types-and-interfaces'],
                'version-constraint' => $vars['version-constraint'],
                'field-version-constraints' => $vars['field-version-constraints'],
                'directive-version-constraints' => $vars['directive-version-constraints'],
                'redundant-root-fields-disabled' => EngineComponentConfiguration::disableRedundantRootTypeMutationFields(),
            ]
        );
    }
}
