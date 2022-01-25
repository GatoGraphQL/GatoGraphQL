<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Component;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Component as GraphQLEndpointForWPComponent;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;
use PoP\Root\App;

trait WPClientTrait
{
    /**
     * Base URL
     */
    protected function getComponentBaseURL(): ?string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
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
        $componentConfiguration = App::getComponent(GraphQLEndpointForWPComponent::class)->getConfiguration();
        return $componentConfiguration->getGraphQLAPIEndpoint();
    }
}
