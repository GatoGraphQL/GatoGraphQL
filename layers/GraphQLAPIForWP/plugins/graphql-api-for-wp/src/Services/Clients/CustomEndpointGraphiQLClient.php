<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;

class CustomEndpointGraphiQLClient extends GraphiQLClient
{
    use CustomEndpointClientTrait;
}
