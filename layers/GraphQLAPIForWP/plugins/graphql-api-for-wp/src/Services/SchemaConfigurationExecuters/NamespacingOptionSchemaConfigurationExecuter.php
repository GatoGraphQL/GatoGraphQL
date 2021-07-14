<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigNamespacingBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;

class NamespacingOptionSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::SCHEMA_NAMESPACING)) {
            return;
        }

        $schemaConfigOptionsBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigOptionsBlockDataItem !== null) {
            /**
             * Namespace Types and Interfaces
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $useNamespacing = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigNamespacingBlock::ATTRIBUTE_NAME_USE_NAMESPACING] ?? null;
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($useNamespacing, [
                    BlockAttributeValues::ENABLED,
                    BlockAttributeValues::DISABLED,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                ComponentModelComponentConfiguration::class,
                ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES
            );
            \add_filter(
                $hookName,
                fn () => $useNamespacing == BlockAttributeValues::ENABLED,
                PHP_INT_MAX
            );
        }
    }

    protected function getBlockClass(): string
    {
        return SchemaConfigNamespacingBlock::class;
    }
}
