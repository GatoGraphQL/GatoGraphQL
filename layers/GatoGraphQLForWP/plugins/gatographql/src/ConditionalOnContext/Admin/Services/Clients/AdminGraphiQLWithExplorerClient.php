<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\Clients;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class AdminGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    private ?EndpointHelpers $endpointHelpers = null;

    final protected function getEndpointHelpers(): EndpointHelpers
    {
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
    }

    /**
     * Endpoint URL or URL Path
     */
    public function getEndpointURLOrURLPath(): ?string
    {
        return $this->getEndpointHelpers()->getAdminGraphQLEndpoint();
    }
}
