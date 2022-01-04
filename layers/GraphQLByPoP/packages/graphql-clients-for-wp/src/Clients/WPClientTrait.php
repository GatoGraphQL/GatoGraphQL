<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\Root\Managers\ComponentManager;
use GraphQLByPoP\GraphQLClientsForWP\Component;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Component as GraphQLEndpointForWPComponent;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

trait WPClientTrait
{
    /**
     * Base URL
     */
    protected function getComponentBaseURL(): ?string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getGraphQLClientsComponentURL();
    }
    /**
     * Base Dir
     */
    protected function getComponentBaseDir(): string
    {
        return dirname(__FILE__, 3);
    }

    /**
     * Endpoint URL or URL Path
     */
    protected function getEndpointURLOrURLPath(): string
    {
        /** @var GraphQLEndpointForWPComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(GraphQLEndpointForWPComponent::class)->getConfiguration();
        return $componentConfiguration->getGraphQLAPIEndpoint();
    }
}
