<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\EndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class EndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected EndpointSchemaConfigurator $endpointSchemaConfigurator
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    protected function getCustomPostType(): string
    {
        /** @var GraphQLEndpointCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLEndpointCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->endpointSchemaConfigurator;
    }
}
