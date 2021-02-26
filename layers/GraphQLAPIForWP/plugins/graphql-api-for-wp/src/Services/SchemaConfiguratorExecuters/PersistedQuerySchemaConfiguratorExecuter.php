<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class PersistedQuerySchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    protected PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator;

    function __construct(
        PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator
    ) {
        $this->persistedQuerySchemaConfigurator = $persistedQuerySchemaConfigurator;
    }

    protected function getPostType(): string
    {
        return GraphQLPersistedQueryPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQuerySchemaConfigurator;
    }
}
