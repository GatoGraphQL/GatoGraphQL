<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAdminSchemaBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class AdminSchemaSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_ADMIN_SCHEMA;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigOptionsBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigOptionsBlockDataItem !== null) {
            /**
             * "Admin" schema
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $enableAdminSchema = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigAdminSchemaBlock::ATTRIBUTE_NAME_ENABLE_ADMIN_SCHEMA] ?? null;
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($enableAdminSchema, [
                    BlockAttributeValues::ENABLED,
                    BlockAttributeValues::DISABLED,
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
                fn () => $enableAdminSchema == BlockAttributeValues::ENABLED,
                PHP_INT_MAX
            );
        }
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigAdminSchemaBlock::class;
    }
}
