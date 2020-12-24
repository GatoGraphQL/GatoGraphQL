<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        return EndpointHelpers::getAdminGraphQLEndpoint();
    }
}
