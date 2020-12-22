<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class PersistedQuerySchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    protected function getPostType(): string
    {
        return GraphQLPersistedQueryPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return new PersistedQuerySchemaConfigurator();
    }
}
