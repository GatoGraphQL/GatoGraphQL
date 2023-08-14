<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Clients;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLByPoP\GraphQLClientsForWP\Clients\VoyagerClient;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class CustomEndpointVoyagerClient extends VoyagerClient
{
    use CustomEndpointClientTrait;

    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        if ($this->graphQLCustomEndpointCustomPostType === null) {
            /** @var GraphQLCustomEndpointCustomPostType */
            $graphQLCustomEndpointCustomPostType = $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
            $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        }
        return $this->graphQLCustomEndpointCustomPostType;
    }
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        if ($this->requestHelperService === null) {
            /** @var RequestHelperServiceInterface */
            $requestHelperService = $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
            $this->requestHelperService = $requestHelperService;
        }
        return $this->requestHelperService;
    }
}
