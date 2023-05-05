<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP\EndpointHandlers;

use PoP\Root\App;
use GraphQLByPoP\GraphQLEndpointForWP\Module;
use GraphQLByPoP\GraphQLEndpointForWP\ModuleConfiguration;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\Root\Services\BasicServiceTrait;
use PoPAPI\GraphQLAPI\Module as GatoGraphQLModule;
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
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        if ($this->isGatoGraphQLEnabled()) {
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
        return $moduleConfiguration->getGatoGraphQLEndpoint();
    }

    /**
     * Check if GrahQL has been enabled
     */
    protected function isGatoGraphQLEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return
            class_exists(GatoGraphQLModule::class)
            && App::getModule(GatoGraphQLModule::class)->isEnabled()
            && !$moduleConfiguration->isGatoGraphQLEndpointDisabled();
    }
}
