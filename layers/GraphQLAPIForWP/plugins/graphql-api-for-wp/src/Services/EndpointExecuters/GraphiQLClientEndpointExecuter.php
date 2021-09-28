<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointGraphiQLClient;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\GraphiQLClientEndpointAnnotator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class GraphiQLClientEndpointExecuter extends AbstractClientEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    protected CustomEndpointGraphiQLClient $customEndpointGraphiQLClient;
    protected GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator;

    #[Required]
    public function autowireGraphiQLClientEndpointExecuter(
        CustomEndpointGraphiQLClient $customEndpointGraphiQLClient,
        GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator,
    ): void {
        $this->customEndpointGraphiQLClient = $customEndpointGraphiQLClient;
        $this->graphiQLClientEndpointAnnotator = $graphiQLClientEndpointAnnotator;
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

    protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface
    {
        return $this->graphiQLClientEndpointAnnotator;
    }
}
