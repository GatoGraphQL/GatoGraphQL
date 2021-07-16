<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractGraphiQLClient;

class GraphiQLWithExplorerClient extends AbstractGraphiQLClient
{
    use GraphiQLWithExplorerClientTrait;
}
