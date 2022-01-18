<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use PoP\Root\App;
use GraphQLByPoP\GraphQLEndpointForWP\Component;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\ComponentModel\Constants\Params;
use PoP\Root\Services\BasicServiceTrait;
use PoP\GraphQLAPI\Component as GraphQLAPIComponent;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLEndpointHandler extends AbstractEndpointHandler
{
    use BasicServiceTrait;

    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        if ($this->isGraphQLAPIEnabled()) {
            parent::initialize();
        }
    }

    /**
     * Provide the endpoint
     */
    protected function getEndpoint(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getGraphQLAPIEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return
            class_exists(GraphQLAPIComponent::class)
            && App::getComponent(GraphQLAPIComponent::class)->isEnabled()
            && !$componentConfiguration->isGraphQLAPIEndpointDisabled();
    }

    /**
     * Indicate this is a GraphQL request
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[Params::SCHEME] = APISchemes::API;
        // Include qualified namespace here (instead of `use`) since we do didn't know if component is installed
        $_REQUEST[Params::DATASTRUCTURE] = $this->getGraphQLDataStructureFormatter()->getName();
        // Enable hooks
        \do_action('EndpointHandler:setDoingGraphQL');
    }
}
