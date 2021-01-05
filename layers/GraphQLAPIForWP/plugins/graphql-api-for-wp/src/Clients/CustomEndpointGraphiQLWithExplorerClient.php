<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient;

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
