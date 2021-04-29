<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    function __construct(protected EndpointHelpers $endpointHelpers)
    {
    }

    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        return $this->endpointHelpers->getAdminConfigurableSchemaGraphQLEndpoint();
    }
}
