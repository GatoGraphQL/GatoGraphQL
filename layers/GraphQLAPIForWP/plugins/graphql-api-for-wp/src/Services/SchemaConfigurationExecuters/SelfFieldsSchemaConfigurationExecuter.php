<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSelfFieldsBlock;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use Symfony\Contracts\Service\Attribute\Required;

class SelfFieldsSchemaConfigurationExecuter extends AbstractDefaultEnableDisableFunctionalitySchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    protected SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock;

    #[Required]
    final public function autowireNamespacingSchemaConfigurationExecuter(
        SchemaConfigSelfFieldsBlock $schemaConfigSelfFieldsBlock,
    ): void {
        $this->schemaConfigSelfFieldsBlock = $schemaConfigSelfFieldsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_SELF_FIELDS;
    }

    protected function getBlock(): BlockInterface
    {
        return $this->schemaConfigSelfFieldsBlock;
    }

    public function getHookComponentConfigurationClass(): string
    {
        return GraphQLServerComponentConfiguration::class;
    }

    public function getHookEnvironmentClass(): string
    {
        return GraphQLServerEnvironment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA;
    }
}
