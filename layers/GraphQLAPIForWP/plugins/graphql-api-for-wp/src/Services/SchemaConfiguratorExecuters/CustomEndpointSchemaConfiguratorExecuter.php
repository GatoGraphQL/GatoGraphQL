<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class CustomEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?CustomEndpointSchemaConfigurator $endpointSchemaConfigurator = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    public function setCustomEndpointSchemaConfigurator(CustomEndpointSchemaConfigurator $endpointSchemaConfigurator): void
    {
        $this->endpointSchemaConfigurator = $endpointSchemaConfigurator;
    }
    protected function getCustomEndpointSchemaConfigurator(): CustomEndpointSchemaConfigurator
    {
        return $this->endpointSchemaConfigurator ??= $this->instanceManager->getInstance(CustomEndpointSchemaConfigurator::class);
    }
    public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    //#[Required]
    final public function autowireCustomEndpointSchemaConfiguratorExecuter(
        CustomEndpointSchemaConfigurator $endpointSchemaConfigurator,
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ): void {
        $this->endpointSchemaConfigurator = $endpointSchemaConfigurator;
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getEndpointSchemaConfigurator();
    }
}
