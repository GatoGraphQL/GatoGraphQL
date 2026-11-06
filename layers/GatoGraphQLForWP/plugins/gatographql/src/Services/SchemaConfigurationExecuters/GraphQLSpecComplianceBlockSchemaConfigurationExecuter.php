<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigGraphQLSpecComplianceBlock;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Module as ComponentModelModule;

class GraphQLSpecComplianceBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    use DefaultEnableDisableBlockAttributeSchemaConfigurationExecuterTrait;

    private ?SchemaConfigGraphQLSpecComplianceBlock $schemaConfigGraphQLSpecComplianceBlock = null;

    final protected function getSchemaConfigGraphQLSpecComplianceBlock(): SchemaConfigGraphQLSpecComplianceBlock
    {
        if ($this->schemaConfigGraphQLSpecComplianceBlock === null) {
            /** @var SchemaConfigGraphQLSpecComplianceBlock */
            $schemaConfigGraphQLSpecComplianceBlock = $this->instanceManager->getInstance(SchemaConfigGraphQLSpecComplianceBlock::class);
            $this->schemaConfigGraphQLSpecComplianceBlock = $schemaConfigGraphQLSpecComplianceBlock;
        }
        return $this->schemaConfigGraphQLSpecComplianceBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::GRAPHQL_SPEC_COMPLIANCE;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigGraphQLSpecComplianceBlock();
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $attributeNameEnvironmentClasses = [
            SchemaConfigGraphQLSpecComplianceBlock::ATTRIBUTE_NAME_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT => ComponentModelEnvironment::PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT,
            SchemaConfigGraphQLSpecComplianceBlock::ATTRIBUTE_NAME_COERCE_OUTPUT_VALUES => ComponentModelEnvironment::COERCE_OUTPUT_VALUES,
            SchemaConfigGraphQLSpecComplianceBlock::ATTRIBUTE_NAME_ADD_RESPONSE_PATH_TO_ERRORS => ComponentModelEnvironment::ADD_RESPONSE_PATH_TO_ERRORS,
            SchemaConfigGraphQLSpecComplianceBlock::ATTRIBUTE_NAME_RETURN_NO_DATA_ON_REQUEST_ERRORS => ComponentModelEnvironment::RETURN_NO_DATA_ON_REQUEST_ERRORS,
        ];
        foreach ($attributeNameEnvironmentClasses as $attributeName => $environmentClass) {
            $this->executeDefaultEnableDisableBlockAttribute(
                $schemaConfigBlockDataItem,
                $attributeName,
                ComponentModelModule::class,
                $environmentClass,
            );
        }
    }
}
