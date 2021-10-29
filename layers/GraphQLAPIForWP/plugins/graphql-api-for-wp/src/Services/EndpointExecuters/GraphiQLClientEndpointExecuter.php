<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointGraphiQLClient;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\GraphiQLClientEndpointAnnotator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use Symfony\Contracts\Service\Attribute\Required;

class GraphiQLClientEndpointExecuter extends AbstractClientEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    private ?CustomEndpointGraphiQLClient $customEndpointGraphiQLClient = null;
    private ?GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator = null;

    public function setCustomEndpointGraphiQLClient(CustomEndpointGraphiQLClient $customEndpointGraphiQLClient): void
    {
        $this->customEndpointGraphiQLClient = $customEndpointGraphiQLClient;
    }
    protected function getCustomEndpointGraphiQLClient(): CustomEndpointGraphiQLClient
    {
        return $this->customEndpointGraphiQLClient ??= $this->instanceManager->getInstance(CustomEndpointGraphiQLClient::class);
    }
    public function setGraphiQLClientEndpointAnnotator(GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator): void
    {
        $this->graphiQLClientEndpointAnnotator = $graphiQLClientEndpointAnnotator;
    }
    protected function getGraphiQLClientEndpointAnnotator(): GraphiQLClientEndpointAnnotator
    {
        return $this->graphiQLClientEndpointAnnotator ??= $this->instanceManager->getInstance(GraphiQLClientEndpointAnnotator::class);
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
        return $this->getCustomEndpointGraphiQLClient();
    }

    protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface
    {
        return $this->getGraphiQLClientEndpointAnnotator();
    }
}
