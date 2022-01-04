<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GraphQLByPoP\GraphQLServer\Component as GraphQLServerComponent;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\BasicService\Component\ComponentConfigurationHelpers;
use PoP\Engine\Component as EngineComponent;
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
        return $this->schemaConfigMutationSchemeBlock ??= $this->instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::NESTED_MUTATIONS;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem !== null) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $mutationScheme = $schemaConfigBlockDataItem['attrs'][SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME] ?? null;
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
                GraphQLServerComponent::class,
                GraphQLServerEnvironment::ENABLE_NESTED_MUTATIONS
            );
            \add_filter(
                $hookName,
                fn () => $mutationScheme != MutationSchemes::STANDARD,
                PHP_INT_MAX
            );
            $hookName = ComponentConfigurationHelpers::getHookName(
                EngineComponent::class,
                EngineEnvironment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS
            );
            \add_filter(
                $hookName,
                fn () => $mutationScheme == MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
                PHP_INT_MAX
            );
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigMutationSchemeBlock();
    }
}
