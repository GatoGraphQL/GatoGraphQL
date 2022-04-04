<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Root\App;
use PoP\Engine\Cache\CacheUtils;
use PoP\Root\Hooks\AbstractHookSet;

class SchemaCacheHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CacheUtils::HOOK_SCHEMA_CACHE_KEY_COMPONENTS,
            $this->getSchemaCacheKeyComponents(...)
        );
    }

    public function getSchemaCacheKeyComponents(array $components): array
    {
        if ($graphQLOperationType = App::getState('graphql-operation-type')) {
            $components['graphql-operation-type'] = $graphQLOperationType;
        }
        $components['nested-mutations-enabled'] = App::getState('nested-mutations-enabled');
        return $components;
    }
}
