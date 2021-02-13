<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient as UpstreamGraphiQLWithExplorerClient;

class GraphiQLWithExplorerClient extends UpstreamGraphiQLWithExplorerClient
{
    use SingleEndpointGraphiQLClientTrait;

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
