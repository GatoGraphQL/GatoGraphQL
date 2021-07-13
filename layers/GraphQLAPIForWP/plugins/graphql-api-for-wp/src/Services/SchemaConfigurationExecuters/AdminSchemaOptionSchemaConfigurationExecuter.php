<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class AdminSchemaOptionSchemaConfigurationExecuter extends AbstractOptionSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA)) {
            return;
        }

        $schemaConfigOptionsBlockDataItem = $this->getSchemaConfigOptionsBlockDataItem($schemaConfigurationID);
        if ($schemaConfigOptionsBlockDataItem !== null) {
            /**
             * "Admin" schema
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $enableAdminSchema = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA] ?? null;
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($enableAdminSchema, [
                    SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_ENABLED,
                    SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_DISABLED,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                ComponentModelComponentConfiguration::class,
                ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA
            );
            \add_filter(
                $hookName,
                fn () => $enableAdminSchema == SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_ENABLED,
                PHP_INT_MAX
            );
        }
    }
}
