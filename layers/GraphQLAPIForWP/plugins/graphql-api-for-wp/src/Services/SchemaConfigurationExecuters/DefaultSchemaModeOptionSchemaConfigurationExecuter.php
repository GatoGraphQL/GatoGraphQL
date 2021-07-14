<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;

class DefaultSchemaModeOptionSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQuerySchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check if it enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA)) {
            return;
        }

        $schemaConfigOptionsBlockDataItem = $this->getSchemaConfigOptionsBlockDataItem($schemaConfigurationID);
        if ($schemaConfigOptionsBlockDataItem !== null) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $defaultSchemaMode = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE] ?? null;
            // Only execute if it has value "public" or "private".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (
                !in_array($defaultSchemaMode, [
                    SchemaModes::PUBLIC_SCHEMA_MODE,
                    SchemaModes::PRIVATE_SCHEMA_MODE,
                ])
            ) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                AccessControlComponentConfiguration::class,
                AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE
            );
            \add_filter(
                $hookName,
                fn () => $defaultSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE,
                PHP_INT_MAX
            );
            /**
             * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
             */
            protected function getSchemaConfigOptionsBlockDataItem(int $schemaConfigurationID): ?array
            {
                /** @var BlockHelpers */
                $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
                /**
                 * @var SchemaConfigOptionsBlock
                 */
                $block = $this->instanceManager->getInstance(SchemaConfigOptionsBlock::class);
                return $blockHelpers->getSingleBlockOfTypeFromCustomPost(
                    $schemaConfigurationID,
                    $block
                );
            }
        }
    }
}
