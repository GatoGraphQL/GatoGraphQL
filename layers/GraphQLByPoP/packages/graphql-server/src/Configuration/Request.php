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
        $editSchema = $_POST[Params::EDIT_SCHEMA] ?? \PoP\Root\App::query(Params::EDIT_SCHEMA);
        if ($editSchema === null) {
            return false;
        }
        return EnvironmentValueHelpers::toBool($editSchema);
    }

    public static function getMutationScheme(): ?string
    {
        if (!Environment::enableSettingMutationSchemeByURLParam()) {
            return null;
        }

        $scheme = $_POST[Params::MUTATION_SCHEME] ?? \PoP\Root\App::query(Params::MUTATION_SCHEME);
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
        $enableGraphQLIntrospection = $_POST[Params::ENABLE_GRAPHQL_INTROSPECTION] ?? \PoP\Root\App::query(Params::ENABLE_GRAPHQL_INTROSPECTION);
        if ($enableGraphQLIntrospection === null) {
            return null;
        }
        return EnvironmentValueHelpers::toBool($enableGraphQLIntrospection);
    }
}
