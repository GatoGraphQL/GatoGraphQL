<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StateManagers;

class AppThreadHelper
{
    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public static function getGraphQLServerContextUniqueID(array $pluginAppGraphQLServerContext): string
    {
        $schemaConfigurationID = $pluginAppGraphQLServerContext[AppThreadContext::SCHEMA_CONFIGURATION_ID] ?? null;
        return (string) ($schemaConfigurationID ?? 0);
    }
}
