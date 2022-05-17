<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use PoP\Root\App;
use GraphQLByPoP\GraphQLEndpointForWP\Module;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\Root\Services\BasicServiceTrait;
use PoPAPI\GraphQLAPI\Module as GraphQLAPIComponent;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

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
    public function getEndpoint(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->getGraphQLAPIEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return
            class_exists(GraphQLAPIComponent::class)
            && App::getComponent(GraphQLAPIComponent::class)->isEnabled()
            && !$componentConfiguration->isGraphQLAPIEndpointDisabled();
    }
}
