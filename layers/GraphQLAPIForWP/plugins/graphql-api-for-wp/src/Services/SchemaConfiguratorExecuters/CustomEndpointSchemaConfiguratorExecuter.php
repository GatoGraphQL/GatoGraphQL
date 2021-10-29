<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CustomEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?CustomEndpointSchemaConfigurator $customEndpointSchemaConfigurator = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    public function setCustomEndpointSchemaConfigurator(CustomEndpointSchemaConfigurator $customEndpointSchemaConfigurator): void
    {
        $this->customEndpointSchemaConfigurator = $customEndpointSchemaConfigurator;
    }
    protected function getCustomEndpointSchemaConfigurator(): CustomEndpointSchemaConfigurator
    {
        return $this->customEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(CustomEndpointSchemaConfigurator::class);
    }
    public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
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
}
