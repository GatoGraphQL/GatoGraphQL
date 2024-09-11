<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Constants\GlobalFieldsSchemaExposure;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigGlobalFieldsBlock;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use GraphQLByPoP\GraphQLServer\Module as GraphQLServerModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class GlobalFieldsBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigGlobalFieldsBlock $schemaConfigGlobalFieldsBlock = null;

    final public function setSchemaConfigGlobalFieldsBlock(SchemaConfigGlobalFieldsBlock $schemaConfigGlobalFieldsBlock): void
    {
        $this->schemaConfigGlobalFieldsBlock = $schemaConfigGlobalFieldsBlock;
    }
    final protected function getSchemaConfigGlobalFieldsBlock(): SchemaConfigGlobalFieldsBlock
    {
        if ($this->schemaConfigGlobalFieldsBlock === null) {
            /** @var SchemaConfigGlobalFieldsBlock */
            $schemaConfigGlobalFieldsBlock = $this->instanceManager->getInstance(SchemaConfigGlobalFieldsBlock::class);
            $this->schemaConfigGlobalFieldsBlock = $schemaConfigGlobalFieldsBlock;
        }
        return $this->schemaConfigGlobalFieldsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::GLOBAL_FIELDS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        /**
         * Default value (if not defined in DB): `default`. Then do nothing
         */
        $schemaExposure = $schemaConfigBlockDataItem['attrs'][SchemaConfigGlobalFieldsBlock::ATTRIBUTE_NAME_SCHEMA_EXPOSURE] ?? null;
        /**
         * Only execute if it has any selectable value (no null, no invented).
         * If "default", then the general settings will already take effect,
         * so do nothing.
         */
        if (
            !in_array($schemaExposure, [
                GlobalFieldsSchemaExposure::DO_NOT_EXPOSE,
                GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY,
                GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES,
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
            GraphQLServerEnvironment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA
        );
        App::addFilter(
            $hookName,
            fn () => $schemaExposure !== GlobalFieldsSchemaExposure::DO_NOT_EXPOSE,
            PHP_INT_MAX
        );

        $hookName = ModuleConfigurationHelpers::getHookName(
            GraphQLServerModule::class,
            GraphQLServerEnvironment::EXPOSE_GLOBAL_FIELDS_IN_ROOT_TYPE_ONLY_IN_GRAPHQL_SCHEMA
        );
        App::addFilter(
            $hookName,
            fn () => $schemaExposure === GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigGlobalFieldsBlock();
    }
}
