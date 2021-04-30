<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\GraphiQLExplorerInCustomEndpointPublicClient\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;

class CustomEndpointGraphiQLWithExplorerClient extends GraphiQLWithExplorerClient
{
    use CustomEndpointClientTrait;
}
