<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAdminFieldsBlock;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use Symfony\Contracts\Service\Attribute\Required;

class AdminFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigAdminFieldsBlock $schemaConfigAdminFieldsBlock = null;

    public function setSchemaConfigAdminFieldsBlock(SchemaConfigAdminFieldsBlock $schemaConfigAdminFieldsBlock): void
    {
        $this->schemaConfigAdminFieldsBlock = $schemaConfigAdminFieldsBlock;
    }
    protected function getSchemaConfigAdminFieldsBlock(): SchemaConfigAdminFieldsBlock
    {
        return $this->schemaConfigAdminFieldsBlock ??= $this->instanceManager->getInstance(SchemaConfigAdminFieldsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_ADMIN_FIELDS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigAdminFieldsBlock();
    }

    public function getHookComponentConfigurationClass(): string
    {
        return ComponentModelComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return ComponentModelEnvironment::ENABLE_ADMIN_SCHEMA;
    }
}
