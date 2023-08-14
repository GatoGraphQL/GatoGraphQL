<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients;

use GatoGraphQL\GatoGraphQL\Services\Clients\CustomEndpointGraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClientTrait;

class CustomEndpointGraphiQLWithExplorerClient extends CustomEndpointGraphiQLClient
{
    use GraphiQLWithExplorerClientTrait;
}
