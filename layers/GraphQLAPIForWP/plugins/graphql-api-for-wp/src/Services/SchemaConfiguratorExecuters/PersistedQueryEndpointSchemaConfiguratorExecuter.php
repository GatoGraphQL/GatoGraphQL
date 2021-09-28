<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    protected PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator;
    protected GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType;

    #[Required]
    public function autowirePersistedQueryEndpointSchemaConfiguratorExecuter(
        PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator,
        GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType,
    ) {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }

    protected function getCustomPostType(): string
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQueryEndpointSchemaConfigurator;
    }
}
