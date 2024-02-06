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
        $schemaConfigurationID = (int) ($pluginAppGraphQLServerContext[AppThreadContext::SCHEMA_CONFIGURATION_ID] ?? 0);
        /**
         * Watch out! This method can be used to generate classnames!
         * Hence, remove the "-" from a negative number.
         * In particualr: `-1` means "No schema config"
         */
        if ($schemaConfigurationID < 0) {
            return '_' . ((string) $schemaConfigurationID * -1);
        }
        return (string) $schemaConfigurationID;
    }
}
