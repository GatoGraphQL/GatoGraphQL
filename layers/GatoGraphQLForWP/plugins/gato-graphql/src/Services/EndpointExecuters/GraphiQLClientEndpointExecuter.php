<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Clients\CustomEndpointGraphiQLClient;
use GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators\GraphiQLClientEndpointAnnotator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;

class GraphiQLClientEndpointExecuter extends AbstractClientEndpointExecuter
{
    private ?CustomEndpointGraphiQLClient $customEndpointGraphiQLClient = null;
    private ?GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator = null;

    final public function setCustomEndpointGraphiQLClient(CustomEndpointGraphiQLClient $customEndpointGraphiQLClient): void
    {
        $this->customEndpointGraphiQLClient = $customEndpointGraphiQLClient;
    }
    final protected function getCustomEndpointGraphiQLClient(): CustomEndpointGraphiQLClient
    {
        if ($this->customEndpointGraphiQLClient === null) {
            /** @var CustomEndpointGraphiQLClient */
            $customEndpointGraphiQLClient = $this->instanceManager->getInstance(CustomEndpointGraphiQLClient::class);
            $this->customEndpointGraphiQLClient = $customEndpointGraphiQLClient;
        }
        return $this->customEndpointGraphiQLClient;
    }
    final public function setGraphiQLClientEndpointAnnotator(GraphiQLClientEndpointAnnotator $graphiQLClientEndpointAnnotator): void
    {
        $this->graphiQLClientEndpointAnnotator = $graphiQLClientEndpointAnnotator;
    }
    final protected function getGraphiQLClientEndpointAnnotator(): GraphiQLClientEndpointAnnotator
    {
        if ($this->graphiQLClientEndpointAnnotator === null) {
            /** @var GraphiQLClientEndpointAnnotator */
            $graphiQLClientEndpointAnnotator = $this->instanceManager->getInstance(GraphiQLClientEndpointAnnotator::class);
            $this->graphiQLClientEndpointAnnotator = $graphiQLClientEndpointAnnotator;
        }
        return $this->graphiQLClientEndpointAnnotator;
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
