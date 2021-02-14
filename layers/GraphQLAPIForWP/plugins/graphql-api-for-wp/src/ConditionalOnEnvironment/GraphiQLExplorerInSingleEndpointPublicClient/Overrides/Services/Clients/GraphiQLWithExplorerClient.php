<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInSingleEndpointPublicClient\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Overrides\Services\Clients\SingleEndpointGraphiQLClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient as UpstreamGraphiQLWithExplorerClient;

class GraphiQLWithExplorerClient extends UpstreamGraphiQLWithExplorerClient
{
    use SingleEndpointGraphiQLClientTrait;
}
