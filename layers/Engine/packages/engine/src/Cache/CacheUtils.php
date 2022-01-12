<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\Root\App;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Hooks\Facades\HooksAPIFacade;

class CacheUtils
{
    public const HOOK_SCHEMA_CACHE_KEY_COMPONENTS = __CLASS__ . ':schema-cache-key-components';

    public static function getSchemaCacheKeyComponents(): array
    {
        $hooksAPI = HooksAPIFacade::getInstance();
        /** @var EngineComponentConfiguration */
        $componentConfiguration = App::getComponent(EngineComponent::class)->getConfiguration();
        return (array)$hooksAPI->applyFilters(
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
