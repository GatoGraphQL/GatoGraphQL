<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use GraphQLByPoP\GraphQLEndpointForWP\Component;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration;
use PoP\API\Response\Schemes as APISchemes;
use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\ComponentModel\Constants\Params;
use PoP\BasicService\BasicServiceTrait;
use PoP\GraphQLAPI\Component;
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
        return ComponentConfiguration::getGraphQLAPIEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        return
            class_exists(Component::class)
            && \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->isEnabled()
            && !ComponentConfiguration::isGraphQLAPIEndpointDisabled();
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
