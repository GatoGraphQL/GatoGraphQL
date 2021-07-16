<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class ViewCustomEndpointSourceEndpointExecuter extends AbstractViewSourceEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
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

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }
}
