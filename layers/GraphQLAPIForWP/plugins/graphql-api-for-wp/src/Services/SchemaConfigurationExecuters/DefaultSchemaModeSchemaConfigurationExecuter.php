<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaModeBlock;
use PoP\AccessControl\Component as AccessControlComponent;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\BasicService\Component\ComponentConfigurationHelpers;

class DefaultSchemaModeSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaModeBlock $schemaConfigSchemaModeBlock = null;

    final public function setSchemaConfigSchemaModeBlock(SchemaConfigSchemaModeBlock $schemaConfigSchemaModeBlock): void
    {
        $this->schemaConfigSchemaModeBlock = $schemaConfigSchemaModeBlock;
    }
    final protected function getSchemaConfigSchemaModeBlock(): SchemaConfigSchemaModeBlock
    {
        return $this->schemaConfigSchemaModeBlock ??= $this->instanceManager->getInstance(SchemaConfigSchemaModeBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem !== null) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $defaultSchemaMode = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaModeBlock::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE] ?? null;
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
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaModeBlock();
    }
}
