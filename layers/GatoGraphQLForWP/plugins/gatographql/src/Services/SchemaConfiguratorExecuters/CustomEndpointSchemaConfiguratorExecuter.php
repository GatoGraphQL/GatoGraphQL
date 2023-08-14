<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;

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
        if ($this->customEndpointSchemaConfigurator === null) {
            /** @var CustomEndpointSchemaConfigurator */
            $customEndpointSchemaConfigurator = $this->instanceManager->getInstance(CustomEndpointSchemaConfigurator::class);
            $this->customEndpointSchemaConfigurator = $customEndpointSchemaConfigurator;
        }
        return $this->customEndpointSchemaConfigurator;
    }
    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        if ($this->graphQLCustomEndpointCustomPostType === null) {
            /** @var GraphQLCustomEndpointCustomPostType */
            $graphQLCustomEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
            $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        }
        return $this->graphQLCustomEndpointCustomPostType;
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
