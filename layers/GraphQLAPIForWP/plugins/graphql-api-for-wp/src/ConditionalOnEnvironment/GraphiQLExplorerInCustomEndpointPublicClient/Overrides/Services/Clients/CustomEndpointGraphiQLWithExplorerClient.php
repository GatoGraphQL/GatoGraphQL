<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInCustomEndpointPublicClient\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class CustomEndpointGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    use CustomEndpointClientTrait;
}
