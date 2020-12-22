<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI;

class Environment
{
    public static function disableGraphQLAPI(): bool
    {
        return getenv('DISABLE_GRAPHQL_API') !== false ? strtolower(getenv('DISABLE_GRAPHQL_API')) == "true" : false;
    }
}
