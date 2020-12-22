<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

trait WPClientTrait
{
    /**
     * Base URL
     *
     * @return string
     */
    protected function getComponentBaseURL(): ?string
    {
        return ComponentConfiguration::getGraphQLClientsComponentURL();
    }
    /**
     * Base Dir
     *
     * @return string
     */
    protected function getComponentBaseDir(): string
    {
        return dirname(__FILE__, 3);
    }

    /**
     * Endpoint URL
     *
     * @return string
     */
    protected function getEndpointURL(): string
    {
        return GraphQLEndpointForWPComponentConfiguration::getGraphQLAPIEndpoint();
    }
}
