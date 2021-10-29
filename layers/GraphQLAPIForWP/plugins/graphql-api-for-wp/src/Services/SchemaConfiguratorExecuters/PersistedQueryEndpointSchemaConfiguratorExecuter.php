<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator = null;
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    public function setPersistedQueryEndpointSchemaConfigurator(PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        return $this->persistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }
    public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}
