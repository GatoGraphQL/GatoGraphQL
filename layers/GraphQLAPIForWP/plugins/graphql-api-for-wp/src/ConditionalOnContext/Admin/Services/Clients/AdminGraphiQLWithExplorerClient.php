<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;
use Symfony\Contracts\Service\Attribute\Required;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    protected EndpointHelpers $endpointHelpers;

    #[Required]
    public function autowireAdminGraphiQLWithExplorerClient(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }

    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        return $this->endpointHelpers->getAdminConfigurableSchemaGraphQLEndpoint();
    }
}
