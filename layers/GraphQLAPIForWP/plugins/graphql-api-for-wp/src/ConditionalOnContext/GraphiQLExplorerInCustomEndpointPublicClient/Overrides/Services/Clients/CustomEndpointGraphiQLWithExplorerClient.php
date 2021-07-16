<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\GraphiQLExplorerInCustomEndpointPublicClient\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointClientTrait;
use GraphQLAPI\GraphQLAPI\Services\Clients\CustomEndpointGraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClientTrait;

class CustomEndpointGraphiQLWithExplorerClient extends CustomEndpointGraphiQLClient
{
    use CustomEndpointClientTrait;
    use GraphiQLWithExplorerClientTrait;
}
