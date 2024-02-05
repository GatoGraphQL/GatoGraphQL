<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadContext;

trait InternalGraphQLServerContainerBuilderFactoryTrait
{
    public function getInternalGraphQLServerContainerClassName(
        array $pluginAppGraphQLServerContext,
        string $containerClassName,
    ): string {
        return 'InternalGraphQLServer_' . $this->getContextUniqueID($pluginAppGraphQLServerContext) . '_' . $containerClassName;
    }

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    protected function getContextUniqueID(array $pluginAppGraphQLServerContext): string
    {
        $persistedQueryID = $pluginAppGraphQLServerContext[AppThreadContext::PERSISTED_QUERY_ID] ?? null;
        return $persistedQueryID ?? '0';
    }
}
