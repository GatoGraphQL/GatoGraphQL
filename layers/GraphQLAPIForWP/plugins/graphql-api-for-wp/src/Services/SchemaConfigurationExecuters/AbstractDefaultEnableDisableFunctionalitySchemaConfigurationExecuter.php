<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;

abstract class AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function getSchemaConfigBlockAttributeName(): string
    {
        return BlockAttributeNames::ENABLED_CONST;
    }

    abstract public function getHookComponentConfigurationClass(): string;

    abstract public function getHookEnvironmentClass(): string;

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem !== null) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $enableFunctionality = $schemaConfigBlockDataItem['attrs'][$this->getSchemaConfigBlockAttributeName()] ?? null;
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($enableFunctionality, [
                    BlockAttributeValues::ENABLED,
                    BlockAttributeValues::DISABLED,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                $this->getHookComponentConfigurationClass(),
                $this->getHookEnvironmentClass(),
            );
            \add_filter(
                $hookName,
                fn () => $enableFunctionality == BlockAttributeValues::ENABLED,
                PHP_INT_MAX
            );
        }
    }
}
