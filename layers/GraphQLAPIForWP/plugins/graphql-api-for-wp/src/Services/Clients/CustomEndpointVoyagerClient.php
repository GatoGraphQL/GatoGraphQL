<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\VoyagerClient;

class CustomEndpointVoyagerClient extends VoyagerClient
{
    use CustomEndpointClientTrait;
}
