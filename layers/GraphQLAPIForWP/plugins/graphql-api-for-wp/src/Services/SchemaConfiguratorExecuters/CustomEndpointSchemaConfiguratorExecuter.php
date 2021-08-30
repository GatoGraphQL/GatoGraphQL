<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class CustomEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected CustomEndpointSchemaConfigurator $endpointSchemaConfigurator
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    protected function getCustomPostType(): string
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->endpointSchemaConfigurator;
    }
}
