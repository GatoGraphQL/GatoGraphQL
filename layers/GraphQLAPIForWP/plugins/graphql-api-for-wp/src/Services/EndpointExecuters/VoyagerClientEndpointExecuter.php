<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointVoyagerClient;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\VoyagerClientEndpointAnnotator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class VoyagerClientEndpointExecuter extends AbstractClientEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
        protected CustomEndpointVoyagerClient $customEndpointVoyagerClient,
        protected VoyagerClientEndpointAnnotator $voyagerClientEndpointExecuter,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
            $graphQLCustomEndpointCustomPostType,
        );
    }

    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS;
    }

    protected function getView(): string
    {
        return RequestParams::VIEW_SCHEMA;
    }

    protected function getClient(): AbstractClient
    {
        return $this->customEndpointVoyagerClient;
    }

    protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface
    {
        return $this->voyagerClientEndpointExecuter;
    }
}
