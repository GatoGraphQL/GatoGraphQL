<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Component;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

trait WPClientTrait
{
    /**
     * Base URL
     */
    protected function getComponentBaseURL(): ?string
    {
        return ComponentConfiguration::getGraphQLClientsComponentURL();
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
        return GraphQLEndpointForWPComponentConfiguration::getGraphQLAPIEndpoint();
    }
}
