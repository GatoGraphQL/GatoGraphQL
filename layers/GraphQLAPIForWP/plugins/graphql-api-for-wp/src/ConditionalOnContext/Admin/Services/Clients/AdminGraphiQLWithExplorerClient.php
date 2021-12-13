<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;
use PoP\ComponentModel\Services\BasicServiceTrait;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    use BasicServiceTrait;

    private ?EndpointHelpers $endpointHelpers = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Endpoint URL or URL Path
     */
    protected function getEndpoint(): string
    {
        return $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint();
    }
}
