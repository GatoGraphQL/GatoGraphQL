<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\Clients\VoyagerClient;

class CustomEndpointVoyagerClient extends VoyagerClient
{
    use CustomEndpointClientTrait;
}
