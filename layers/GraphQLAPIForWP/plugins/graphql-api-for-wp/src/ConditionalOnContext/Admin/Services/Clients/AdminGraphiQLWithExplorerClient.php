<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;
use PoP\ComponentModel\Services\BasicServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    use BasicServiceTrait;

    private ?EndpointHelpers $endpointHelpers = null;

    public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        return $this->getEndpointHelpers()->getAdminConfigurableSchemaGraphQLEndpoint();
    }
}
