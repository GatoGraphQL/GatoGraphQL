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
        $schemaConfigurationID = $pluginAppGraphQLServerContext[AppThreadContext::SCHEMA_CONFIGURATION_ID] ?? null;
        return $schemaConfigurationID ?? '0';
    }
}
