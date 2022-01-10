<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Cache\CacheUtils;
use PoP\BasicService\AbstractHookSet;

class SchemaCacheHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            CacheUtils::HOOK_SCHEMA_CACHE_KEY_COMPONENTS,
            array($this, 'getSchemaCacheKeyComponents')
        );
    }

    public function getSchemaCacheKeyComponents(array $components): array
    {
        if ($graphQLOperationType = \PoP\Root\App::getState('graphql-operation-type')) {
            $components['graphql-operation-type'] = $graphQLOperationType;
        }
        $components['nested-mutations-enabled'] = \PoP\Root\App::getState('nested-mutations-enabled');
        return $components;
    }
}
