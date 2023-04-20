<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPrivatePersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PrivatePersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class PrivatePersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?PrivatePersistedQueryEndpointSchemaConfigurator $privatePersistedQueryEndpointSchemaConfigurator = null;
    private ?GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType = null;

    final public function setPrivatePersistedQueryEndpointSchemaConfigurator(PrivatePersistedQueryEndpointSchemaConfigurator $privatePersistedQueryEndpointSchemaConfigurator): void
    {
        $this->privatePersistedQueryEndpointSchemaConfigurator = $privatePersistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPrivatePersistedQueryEndpointSchemaConfigurator(): PrivatePersistedQueryEndpointSchemaConfigurator
    {
        /** @var PrivatePersistedQueryEndpointSchemaConfigurator */
        return $this->privatePersistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PrivatePersistedQueryEndpointSchemaConfigurator::class);
    }
    final public function setGraphQLPrivatePersistedQueryEndpointCustomPostType(GraphQLPrivatePersistedQueryEndpointCustomPostType $graphQLPrivatePersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPrivatePersistedQueryEndpointCustomPostType = $graphQLPrivatePersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPrivatePersistedQueryEndpointCustomPostType(): GraphQLPrivatePersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPrivatePersistedQueryEndpointCustomPostType */
        return $this->graphQLPrivatePersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPrivatePersistedQueryEndpointCustomPostType::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLPrivatePersistedQueryEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPrivatePersistedQueryEndpointSchemaConfigurator();
    }
}
