<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;

abstract class AbstractDefaultEnableDisableFunctionalityBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    use DefaultEnableDisableBlockAttributeSchemaConfigurationExecuterTrait;

    public function getSchemaConfigBlockAttributeName(): string
    {
        return BlockAttributeNames::ENABLED_CONST;
    }

    abstract public function getHookModuleClass(): string;

    abstract public function getHookEnvironmentClass(): string;

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $this->executeDefaultEnableDisableBlockAttribute(
            $schemaConfigBlockDataItem,
            $this->getSchemaConfigBlockAttributeName(),
            $this->getHookModuleClass(),
            $this->getHookEnvironmentClass(),
        );
    }
}
