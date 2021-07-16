<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class CustomEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }
    
    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }
}
