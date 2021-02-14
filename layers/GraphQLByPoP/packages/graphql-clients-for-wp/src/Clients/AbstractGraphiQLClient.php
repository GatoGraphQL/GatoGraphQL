<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;

abstract class AbstractGraphiQLClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     *
     * @return boolean
     */
    protected function isClientDisabled(): bool
    {
        return ComponentConfiguration::isGraphiQLClientEndpointDisabled();
    }
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getGraphiQLClientEndpoint();
    }

    /**
     * Use GraphiQL Explorer?
     * Overridable by GraphQL API for WP, to decided based on each screen
     */
    protected function useGraphiQLExplorer(): bool
    {
        return ComponentConfiguration::useGraphiQLExplorer();
    }
}
