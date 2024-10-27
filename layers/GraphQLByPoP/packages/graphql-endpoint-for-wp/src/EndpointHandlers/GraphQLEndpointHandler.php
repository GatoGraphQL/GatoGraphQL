<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use PoP\Root\App;
use GraphQLByPoP\GraphQLEndpointForWP\Module;
use GraphQLByPoP\GraphQLEndpointForWP\ModuleConfiguration;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoPAPI\GraphQLAPI\Module as GraphQLAPIModule;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLEndpointHandler extends AbstractEndpointHandler
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        if ($this->graphQLDataStructureFormatter === null) {
            /** @var GraphQLDataStructureFormatter */
            $graphQLDataStructureFormatter = $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
            $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
        }
        return $this->graphQLDataStructureFormatter;
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getGraphQLAPIEndpoint();
    }

    /**
     * Check if the GrahQL API has been enabled
     */
    protected function isGraphQLAPIEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return
            class_exists(GraphQLAPIModule::class)
            && App::getModule(GraphQLAPIModule::class)->isEnabled()
            && !$moduleConfiguration->isGraphQLAPIEndpointDisabled();
    }
}
