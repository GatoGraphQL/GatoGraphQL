<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

use GraphQLByPoP\GraphQLServer\Constants\Params;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class Request
{
    public static function editSchema(): bool
    {
        if (isset($_REQUEST[Params::EDIT_SCHEMA])) {
            return EnvironmentValueHelpers::toBool($_REQUEST[Params::EDIT_SCHEMA]);
        }
        return false;
    }

    public static function getMutationScheme(): ?string
    {
        if (isset($_REQUEST[Params::MUTATION_SCHEME])) {
            $scheme = $_REQUEST[Params::MUTATION_SCHEME];
            $schemes = [
                MutationSchemes::STANDARD,
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
            ];
            if (in_array($scheme, $schemes)) {
                return $scheme;
            }
        }
        return null;
    }

    public static function enableGraphQLIntrospection(): ?bool
    {
        if (isset($_REQUEST[Params::ENABLE_GRAPHQL_INTROSPECTION])) {
            return EnvironmentValueHelpers::toBool($_REQUEST[Params::ENABLE_GRAPHQL_INTROSPECTION]);
        }
        return null;
    }
}
