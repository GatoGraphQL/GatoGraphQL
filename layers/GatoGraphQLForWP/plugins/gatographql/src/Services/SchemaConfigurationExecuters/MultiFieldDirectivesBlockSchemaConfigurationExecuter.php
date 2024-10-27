<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMultiFieldDirectivesBlock;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\Environment as GraphQLParserEnvironment;

class MultiFieldDirectivesBlockSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigMultiFieldDirectivesBlock $schemaConfigMultiFieldDirectivesBlock = null;

    final protected function getSchemaConfigMultiFieldDirectivesBlock(): SchemaConfigMultiFieldDirectivesBlock
    {
        if ($this->schemaConfigMultiFieldDirectivesBlock === null) {
            /** @var SchemaConfigMultiFieldDirectivesBlock */
            $schemaConfigMultiFieldDirectivesBlock = $this->instanceManager->getInstance(SchemaConfigMultiFieldDirectivesBlock::class);
            $this->schemaConfigMultiFieldDirectivesBlock = $schemaConfigMultiFieldDirectivesBlock;
        }
        return $this->schemaConfigMultiFieldDirectivesBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MULTIFIELD_DIRECTIVES;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigMultiFieldDirectivesBlock();
    }

    public function getHookModuleClass(): string
    {
        return GraphQLParserModule::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return GraphQLParserEnvironment::ENABLE_MULTIFIELD_DIRECTIVES;
    }
}
