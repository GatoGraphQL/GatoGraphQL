<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQuerySchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQuerySchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected PersistedQuerySchemaConfigurator $persistedQuerySchemaConfigurator
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    protected function getCustomPostType(): string
    {
        /** @var GraphQLPersistedQueryCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLPersistedQueryCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQuerySchemaConfigurator;
    }
}
