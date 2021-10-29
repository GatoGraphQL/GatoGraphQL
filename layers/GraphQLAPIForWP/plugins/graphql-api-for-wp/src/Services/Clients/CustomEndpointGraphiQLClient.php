<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use PoP\ComponentModel\Services\BasicServiceTrait;

class CustomEndpointGraphiQLClient extends GraphiQLClient
{
    use CustomEndpointClientTrait;
    use BasicServiceTrait;
}
