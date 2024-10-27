<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigComposableDirectivesBlock;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\Environment as GraphQLParserEnvironment;

class ComposableDirectivesBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigComposableDirectivesBlock $schemaConfigComposableDirectivesBlock = null;

    final protected function getSchemaConfigComposableDirectivesBlock(): SchemaConfigComposableDirectivesBlock
    {
        if ($this->schemaConfigComposableDirectivesBlock === null) {
            /** @var SchemaConfigComposableDirectivesBlock */
            $schemaConfigComposableDirectivesBlock = $this->instanceManager->getInstance(SchemaConfigComposableDirectivesBlock::class);
            $this->schemaConfigComposableDirectivesBlock = $schemaConfigComposableDirectivesBlock;
        }
        return $this->schemaConfigComposableDirectivesBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::COMPOSABLE_DIRECTIVES;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigComposableDirectivesBlock();
    }

    public function getHookModuleClass(): string
    {
        return GraphQLParserModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return GraphQLParserEnvironment::ENABLE_COMPOSABLE_DIRECTIVES;
    }
}
