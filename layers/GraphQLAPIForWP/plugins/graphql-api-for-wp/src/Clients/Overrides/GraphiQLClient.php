<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients\Overrides;

class GraphiQLClient extends \GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient
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
