<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Server;

class GraphQLServer extends AbstractGraphQLServer
{
    protected function areFeedbackAndTracingStoresAlreadyCreated(): bool
    {
        return false;
    }
}
