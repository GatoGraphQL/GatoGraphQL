<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use PoP\ComponentModel\App;
use PoP\Root\Module\ModuleConfigurationHelpers;

abstract class AbstractSchemaMutationsBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock = null;

    final public function setSchemaConfigPayloadTypesForMutationsBlock(SchemaConfigPayloadTypesForMutationsBlock $schemaConfigPayloadTypesForMutationsBlock): void
    {
        $this->schemaConfigPayloadTypesForMutationsBlock = $schemaConfigPayloadTypesForMutationsBlock;
    }
    final protected function getSchemaConfigPayloadTypesForMutationsBlock(): SchemaConfigPayloadTypesForMutationsBlock
    {
        if ($this->schemaConfigPayloadTypesForMutationsBlock === null) {
            /** @var SchemaConfigPayloadTypesForMutationsBlock */
            $schemaConfigPayloadTypesForMutationsBlock = $this->instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);
            $this->schemaConfigPayloadTypesForMutationsBlock = $schemaConfigPayloadTypesForMutationsBlock;
        }
        return $this->schemaConfigPayloadTypesForMutationsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MUTATIONS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        /**
         * Default value (if not defined in DB): `default`. Then do nothing
         */
        $usePayloadType = $schemaConfigBlockDataItem['attrs'][SchemaConfigPayloadTypesForMutationsBlock::ATTRIBUTE_NAME_USE_PAYLOAD_TYPE] ?? null;

        /**
         * Only execute if it has any selectable value (no null, no invented).
         * If "default", then the general settings will already take effect,
         * so do nothing.
         */
        if (
            !in_array($usePayloadType, [
                MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS,
                MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
                MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
            ])
        ) {
            return;
        }

        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookModuleClass = $this->getHookModuleClass();
        $hookName = ModuleConfigurationHelpers::getHookName(
            $hookModuleClass,
            $this->getHookUsePayloadableEnvironmentClass(),
        );
        App::addFilter(
            $hookName,
            fn () => $usePayloadType !== MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
            PHP_INT_MAX
        );
        $hookName = ModuleConfigurationHelpers::getHookName(
            $hookModuleClass,
            $this->getHookAddFieldsToQueryPayloadableEnvironmentClass(),
        );
        App::addFilter(
            $hookName,
            fn () => $usePayloadType === MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
            PHP_INT_MAX
        );
    }

    abstract public function getHookModuleClass(): string;

    abstract public function getHookUsePayloadableEnvironmentClass(): string;

    abstract public function getHookAddFieldsToQueryPayloadableEnvironmentClass(): string;

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigPayloadTypesForMutationsBlock();
    }
}
