<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadContext;
use PoP\Root\Container\SystemContainerBuilderFactory;

class InternalGraphQLServerSystemContainerBuilderFactory extends SystemContainerBuilderFactory
{
    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function __construct(
        private array $pluginAppGraphQLServerContext,
    ){        
    }

    public function getContainerClassName(): string
    {
        return 'InternalGraphQLServer_' . $this->getContextUniqueID() . '_' . parent::getContainerClassName();
    }

    protected function getContextUniqueID(): string
    {
        $persistedQueryID = $this->pluginAppGraphQLServerContext[AppThreadContext::PERSISTED_QUERY_ID] ?? null;
        return $persistedQueryID ?? '0';
    }
}
