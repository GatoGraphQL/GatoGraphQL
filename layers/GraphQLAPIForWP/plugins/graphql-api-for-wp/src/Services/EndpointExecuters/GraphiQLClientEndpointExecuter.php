<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointGraphiQLClient;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class GraphiQLClientEndpointExecuter extends AbstractClientEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
        protected CustomEndpointGraphiQLClient $customEndpointGraphiQLClient,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $graphQLCustomEndpointCustomPostType,
        );
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check the client has not been disabled in the CPT
        global $post;
        /** @var GraphQLCustomEndpointCustomPostType */
        $customPostType = $this->getCustomPostType();
        if (!$customPostType->isGraphiQLEnabled($post)) {
            return false;
        }
        
        return true;
    }

    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS;
    }
    
    protected function getView(): string
    {
        return RequestParams::VIEW_GRAPHIQL;
    }

    protected function getClient(): AbstractClient
    {
        return $this->customEndpointGraphiQLClient;
    }
}
