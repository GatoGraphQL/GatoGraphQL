<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPublicPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PublicPersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class PublicPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    private ?PublicPersistedQueryEndpointSchemaConfigurator $publicPersistedQueryEndpointSchemaConfigurator = null;
    private ?GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType = null;

    final public function setPublicPersistedQueryEndpointSchemaConfigurator(PublicPersistedQueryEndpointSchemaConfigurator $publicPersistedQueryEndpointSchemaConfigurator): void
    {
        $this->publicPersistedQueryEndpointSchemaConfigurator = $publicPersistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPublicPersistedQueryEndpointSchemaConfigurator(): PublicPersistedQueryEndpointSchemaConfigurator
    {
        /** @var PublicPersistedQueryEndpointSchemaConfigurator */
        return $this->publicPersistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PublicPersistedQueryEndpointSchemaConfigurator::class);
    }
    final public function setGraphQLPublicPersistedQueryEndpointCustomPostType(GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPublicPersistedQueryEndpointCustomPostType = $graphQLPublicPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPublicPersistedQueryEndpointCustomPostType(): GraphQLPublicPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPublicPersistedQueryEndpointCustomPostType */
        return $this->graphQLPublicPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPublicPersistedQueryEndpointCustomPostType::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLPublicPersistedQueryEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPublicPersistedQueryEndpointSchemaConfigurator();
    }
}
