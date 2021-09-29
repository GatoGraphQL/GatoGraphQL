<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;

abstract class AbstractGraphiQLClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        return ComponentConfiguration::isGraphiQLClientEndpointDisabled();
    }
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getGraphiQLClientEndpoint();
    }
}
