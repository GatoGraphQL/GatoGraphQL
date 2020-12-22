<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class Request
{
    public const URLPARAM_EDIT_SCHEMA = 'edit_schema';
    public const URLPARAM_MUTATION_SCHEME = 'mutation_scheme';
    public const URLPARAM_ENABLE_GRAPHQL_INTROSPECTION = 'enable_graphql_introspection';

    public static function editSchema(): bool
    {
        if (isset($_REQUEST[self::URLPARAM_EDIT_SCHEMA])) {
            return EnvironmentValueHelpers::toBool($_REQUEST[self::URLPARAM_EDIT_SCHEMA]);
        }
        return false;
    }

    public static function getMutationScheme(): ?string
    {
        if (isset($_REQUEST[self::URLPARAM_MUTATION_SCHEME])) {
            $scheme = $_REQUEST[self::URLPARAM_MUTATION_SCHEME];
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
        if (isset($_REQUEST[self::URLPARAM_ENABLE_GRAPHQL_INTROSPECTION])) {
            return EnvironmentValueHelpers::toBool($_REQUEST[self::URLPARAM_ENABLE_GRAPHQL_INTROSPECTION]);
        }
        return null;
    }
}
