<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Engine\Module as EngineModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class MutationSchemeBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigMutationSchemeBlock $schemaConfigMutationSchemeBlock = null;

    final protected function getSchemaConfigMutationSchemeBlock(): SchemaConfigMutationSchemeBlock
    {
        if ($this->schemaConfigMutationSchemeBlock === null) {
            /** @var SchemaConfigMutationSchemeBlock */
            $schemaConfigMutationSchemeBlock = $this->instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);
            $this->schemaConfigMutationSchemeBlock = $schemaConfigMutationSchemeBlock;
        }
        return $this->schemaConfigMutationSchemeBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
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
        App::addFilter(
            $hookName,
            fn () => $mutationScheme !== MutationSchemes::STANDARD,
            PHP_INT_MAX
        );
        $hookName = ModuleConfigurationHelpers::getHookName(
            EngineModule::class,
            EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS
        );
        App::addFilter(
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
