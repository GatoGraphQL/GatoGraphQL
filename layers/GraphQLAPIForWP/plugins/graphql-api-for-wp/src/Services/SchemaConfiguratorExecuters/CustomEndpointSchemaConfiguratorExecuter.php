<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class CustomEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?CustomEndpointSchemaConfigurator $customEndpointSchemaConfigurator = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    final public function setCustomEndpointSchemaConfigurator(CustomEndpointSchemaConfigurator $customEndpointSchemaConfigurator): void
    {
        $this->customEndpointSchemaConfigurator = $customEndpointSchemaConfigurator;
    }
    final protected function getCustomEndpointSchemaConfigurator(): CustomEndpointSchemaConfigurator
    {
        /** @var CustomEndpointSchemaConfigurator */
        return $this->customEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(CustomEndpointSchemaConfigurator::class);
    }
    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getCustomEndpointSchemaConfigurator();
    }

    protected function getLoadingCPTSchemaConfiguratorModule(): string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }
}
