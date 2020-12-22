<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\PersistedQueries;

use PoP\API\PersistedQueries\AbstractPersistedQueryManager;

class GraphQLPersistedQueryManager extends AbstractPersistedQueryManager implements GraphQLPersistedQueryManagerInterface
{
    protected function addQueryResolutionToSchema(): bool
    {
        return false;
    }
}
