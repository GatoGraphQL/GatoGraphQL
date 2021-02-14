<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInCustomEndpointPublicClient\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointClientTrait;
use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointGraphiQLClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class CustomEndpointGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    use CustomEndpointClientTrait;
    use CustomEndpointGraphiQLClientTrait;

    /**
     * Use GraphiQL Explorer for this screen?
     */
    protected function useGraphiQLExplorer(): bool
    {
        return
            parent::useGraphiQLExplorer()
            && $this->isGraphiQLExplorerOptionEnabled();
    }
}
