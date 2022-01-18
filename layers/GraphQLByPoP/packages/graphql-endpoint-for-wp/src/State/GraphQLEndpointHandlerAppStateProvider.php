<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\State;

use GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers\GraphQLEndpointHandler;
use PoPAPI\APIEndpoints\EndpointHandlerInterface;
use PoPAPI\APIEndpointsForWP\State\AbstractAPIEndpointHandlerAppStateProvider;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLEndpointHandlerAppStateProvider extends AbstractAPIEndpointHandlerAppStateProvider
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;
    private ?GraphQLEndpointHandler $graphQLEndpointHandler = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    final public function setGraphQLEndpointHandler(GraphQLEndpointHandler $graphQLEndpointHandler): void
    {
        $this->graphQLEndpointHandler = $graphQLEndpointHandler;
    }
    final protected function getGraphQLEndpointHandler(): GraphQLEndpointHandler
    {
        return $this->graphQLEndpointHandler ??= $this->instanceManager->getInstance(GraphQLEndpointHandler::class);
    }

    protected function getEndpointHandler(): EndpointHandlerInterface
    {
        return $this->getGraphQLEndpointHandler();
    }

    public function initialize(array &$state): void
    {
        parent::initialize($state);
        $state['datastructure'] = $this->getGraphQLDataStructureFormatter()->getName();
    }
}
