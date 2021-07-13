<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\Environment as EngineEnvironment;

class MutationSchemeOptionSchemaConfigurationExecuter extends AbstractOptionSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(OperationalFunctionalityModuleResolver::NESTED_MUTATIONS)) {
            return;
        }

        $schemaConfigOptionsBlockDataItem = $this->getSchemaConfigOptionsBlockDataItem($schemaConfigurationID);
        if ($schemaConfigOptionsBlockDataItem !== null) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $mutationScheme = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? null;
            // Only execute if it has value "standard", "nested" or "lean_nested".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($mutationScheme, [
                    MutationSchemes::STANDARD,
                    MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
                    MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                GraphQLServerComponentConfiguration::class,
                GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS
            );
            \add_filter(
                $hookName,
                fn () => $mutationScheme != MutationSchemes::STANDARD,
                PHP_INT_MAX
            );
            $hookName = ComponentConfigurationHelpers::getHookName(
                EngineComponentConfiguration::class,
                EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS
            );
            \add_filter(
                $hookName,
                fn () => $mutationScheme == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
                PHP_INT_MAX
            );
        }
    }
}
