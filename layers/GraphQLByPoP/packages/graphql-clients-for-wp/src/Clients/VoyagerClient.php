<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;

class VoyagerClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     *
     * @return boolean
     */
    protected function isClientDisabled(): bool
    {
        return ComponentConfiguration::isVoyagerClientEndpointDisabled();
    }
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getVoyagerClientEndpoint();
    }
    protected function getClientRelativePath(): string
    {
        return '/clients/voyager';
    }
    protected function getJSFilename(): string
    {
        return 'voyager.js';
    }
}
