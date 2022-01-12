<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointVoyagerClient;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\ClientEndpointAnnotatorInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\VoyagerClientEndpointAnnotator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;

class VoyagerClientEndpointExecuter extends AbstractClientEndpointExecuter
{
    private ?CustomEndpointVoyagerClient $customEndpointVoyagerClient = null;
    private ?VoyagerClientEndpointAnnotator $voyagerClientEndpointAnnotator = null;

    final public function setCustomEndpointVoyagerClient(CustomEndpointVoyagerClient $customEndpointVoyagerClient): void
    {
        $this->customEndpointVoyagerClient = $customEndpointVoyagerClient;
    }
    final protected function getCustomEndpointVoyagerClient(): CustomEndpointVoyagerClient
    {
        return $this->customEndpointVoyagerClient ??= $this->instanceManager->getInstance(CustomEndpointVoyagerClient::class);
    }
    final public function setVoyagerClientEndpointAnnotator(VoyagerClientEndpointAnnotator $voyagerClientEndpointAnnotator): void
    {
        $this->voyagerClientEndpointAnnotator = $voyagerClientEndpointAnnotator;
    }
    final protected function getVoyagerClientEndpointAnnotator(): VoyagerClientEndpointAnnotator
    {
        return $this->voyagerClientEndpointAnnotator ??= $this->instanceManager->getInstance(VoyagerClientEndpointAnnotator::class);
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
        return $this->getCustomEndpointVoyagerClient();
    }

    protected function getClientEndpointAnnotator(): ClientEndpointAnnotatorInterface
    {
        return $this->getVoyagerClientEndpointAnnotator();
    }
}
