<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients\Overrides;

class GraphiQLWithExplorerClient extends \GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient
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
