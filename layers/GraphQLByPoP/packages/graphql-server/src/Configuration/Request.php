<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

use GraphQLByPoP\GraphQLServer\Constants\Params;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\Root\Component\EnvironmentValueHelpers;

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
        if (!Environment::enableSettingMutationSchemeByURLParam()) {
            return null;
        }

        $scheme = $_REQUEST[Params::MUTATION_SCHEME] ?? null;
        if ($scheme === null) {
            return null;
        }

        $schemes = [
            MutationSchemes::STANDARD,
            MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
        ];
        if (!in_array($scheme, $schemes)) {
            return null;
        }

        return $scheme;
    }

    public static function enableGraphQLIntrospection(): ?bool
    {
        if (isset($_REQUEST[Params::ENABLE_GRAPHQL_INTROSPECTION])) {
            return EnvironmentValueHelpers::toBool($_REQUEST[Params::ENABLE_GRAPHQL_INTROSPECTION]);
        }
        return null;
    }
}
