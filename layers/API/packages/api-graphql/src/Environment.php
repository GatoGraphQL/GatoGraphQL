<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI;

class Environment
{
    public final const PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT = 'PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT';

    public static function disableGatoGraphQL(): bool
    {
        $envValue = getenv('DISABLE_GRAPHQL_API');
        return $envValue !== false ? strtolower($envValue) === "true" : false;
    }
}
