<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Constants\Params;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\Root\Module\EnvironmentValueHelpers;

class Request
{
    public static function editSchema(): bool
    {
        $editSchema = App::request(Params::EDIT_SCHEMA) ?? App::query(Params::EDIT_SCHEMA);
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

        $scheme = App::request(Params::MUTATION_SCHEME) ?? App::query(Params::MUTATION_SCHEME);
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
        $enableGraphQLIntrospection = App::request(Params::ENABLE_GRAPHQL_INTROSPECTION) ?? App::query(Params::ENABLE_GRAPHQL_INTROSPECTION);
        if ($enableGraphQLIntrospection === null) {
            return null;
        }
        return EnvironmentValueHelpers::toBool($enableGraphQLIntrospection);
    }
}
