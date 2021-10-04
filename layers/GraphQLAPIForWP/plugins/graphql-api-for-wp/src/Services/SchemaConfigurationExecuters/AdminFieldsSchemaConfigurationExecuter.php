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
    protected SchemaConfigAdminFieldsBlock $schemaConfigAdminFieldsBlock;

    #[Required]
    final public function autowireAdminFieldsSchemaConfigurationExecuter(
        SchemaConfigAdminFieldsBlock $schemaConfigAdminFieldsBlock,
    ): void {
        $this->schemaConfigAdminFieldsBlock = $schemaConfigAdminFieldsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_ADMIN_FIELDS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->schemaConfigAdminFieldsBlock;
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
