<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class ViewPersistedQueryEndpointSourceEndpointExecuter extends AbstractViewSourceEndpointExecuter implements PersistedQueryEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }
    
    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    } 
}
