<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI;

class Environment
{
    public final const PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT = 'PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT';

    public static function disableGatoGraphQL(): bool
    {
        return getenv('DISABLE_GRAPHQL_API') !== false ? strtolower(getenv('DISABLE_GRAPHQL_API')) === "true" : false;
    }
}
