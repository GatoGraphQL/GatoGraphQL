<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryCustomPostType;
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

    protected function getCustomPostType(): string
    {
        return GraphQLPersistedQueryCustomPostType::CUSTOM_POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQuerySchemaConfigurator;
    }
}
