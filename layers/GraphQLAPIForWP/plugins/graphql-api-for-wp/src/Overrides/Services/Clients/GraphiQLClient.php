<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient as UpstreamGraphiQLClient;

class GraphiQLClient extends UpstreamGraphiQLClient
{
    use SingleEndpointGraphiQLClientTrait;
}
