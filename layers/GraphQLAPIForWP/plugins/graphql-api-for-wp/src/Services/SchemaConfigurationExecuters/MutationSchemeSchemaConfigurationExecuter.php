<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\Root\Module\ModuleConfigurationHelpers;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\Environment as EngineEnvironment;

class MutationSchemeSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigMutationSchemeBlock $schemaConfigMutationSchemeBlock = null;

    final public function setSchemaConfigMutationSchemeBlock(SchemaConfigMutationSchemeBlock $schemaConfigMutationSchemeBlock): void
    {
        $this->schemaConfigMutationSchemeBlock = $schemaConfigMutationSchemeBlock;
    }
    final protected function getSchemaConfigMutationSchemeBlock(): SchemaConfigMutationSchemeBlock
    {
        /** @var SchemaConfigMutationSchemeBlock */
        return $this->schemaConfigMutationSchemeBlock ??= $this->instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        /**
         * Default value (if not defined in DB): `default`. Then do nothing
         */
        $mutationScheme = $schemaConfigBlockDataItem['attrs'][SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? null;
        /**
         * Only execute if it has any selectable value (no null, no invented).
         * If "default", then the general settings will already take effect,
         * so do nothing.
         */
        if (
            !in_array($mutationScheme, [
                MutationSchemes::STANDARD,
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
            ])
        ) {
            return;
        }
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            GraphQLServerModule::class,
            GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS
        );
        \add_filter(
            $hookName,
            fn () => $mutationScheme !== MutationSchemes::STANDARD,
            PHP_INT_MAX
        );
        $hookName = ModuleConfigurationHelpers::getHookName(
            EngineModule::class,
            EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS
        );
        \add_filter(
            $hookName,
            fn () => $mutationScheme === MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigMutationSchemeBlock();
    }
}
