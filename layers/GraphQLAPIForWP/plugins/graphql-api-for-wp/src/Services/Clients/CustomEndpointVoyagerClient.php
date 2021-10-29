<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\VoyagerClient;
use PoP\ComponentModel\Services\BasicServiceTrait;

class CustomEndpointVoyagerClient extends VoyagerClient
{
    use CustomEndpointClientTrait;
    use BasicServiceTrait;
}
