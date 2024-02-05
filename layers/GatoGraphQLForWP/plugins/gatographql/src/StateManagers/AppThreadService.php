<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StateManagers;

use PoP\Root\Services\BasicServiceTrait;

class AppThreadService implements AppThreadServiceInterface
{
    use BasicServiceTrait;

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function getGraphQLServerContextUniqueID(array $pluginAppGraphQLServerContext): string
    {
        $persistedQueryID = $pluginAppGraphQLServerContext[AppThreadContext::PERSISTED_QUERY_ID] ?? null;
        return $persistedQueryID ?? '0';
    }
}
