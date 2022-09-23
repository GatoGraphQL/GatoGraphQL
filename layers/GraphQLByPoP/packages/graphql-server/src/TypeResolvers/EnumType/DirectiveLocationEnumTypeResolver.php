<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Directives\DirectiveLocations;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;

class DirectiveLocationEnumTypeResolver extends AbstractIntrospectionEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveLocation';
    }

    /**
     * Because this GraphQL server is code-first, the resolver
     * definitions are provided via PHP code, and not via the
     * GraphQL schema (as with SDL-first servers).
     *
     * Then, "Schema Type" directive locations are never used,
     * and exposing this information in the schema can be
     * disabled (it's still enabled by default).
     *
     * @return string[]
     */
    public function getEnumValues(): array
    {
        /**
         * All the enums below are "Query Type",
         * also called ExecutableDirectiveLocation
         *
         * @see https://spec.graphql.org/draft/#ExecutableDirectiveLocation
         */
        $queryTypeDirectiveLocations = [
            DirectiveLocations::QUERY,
            DirectiveLocations::MUTATION,
            DirectiveLocations::SUBSCRIPTION,
            DirectiveLocations::FIELD,
            DirectiveLocations::FRAGMENT_DEFINITION,
            DirectiveLocations::FRAGMENT_SPREAD,
            DirectiveLocations::INLINE_FRAGMENT,
            DirectiveLocations::VARIABLE_DEFINITION,
        ];

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->exposeSchemaTypeDirectiveLocations()) {
            return $queryTypeDirectiveLocations;
        }

        /**
         * All the enums below are "Schema Type",
         * also called TypeSystemDirectiveLocation
         *
         * @see https://spec.graphql.org/draft/#TypeSystemDirectiveLocation
         */
        $schemaTypeDirectiveLocations = [
            DirectiveLocations::SCHEMA,
            DirectiveLocations::SCALAR,
            DirectiveLocations::OBJECT,
            DirectiveLocations::FIELD_DEFINITION,
            DirectiveLocations::ARGUMENT_DEFINITION,
            DirectiveLocations::INTERFACE,
            DirectiveLocations::UNION,
            DirectiveLocations::ENUM,
            DirectiveLocations::ENUM_VALUE,
            DirectiveLocations::INPUT_OBJECT,
            DirectiveLocations::INPUT_FIELD_DEFINITION,
        ];
        return [
            ...$queryTypeDirectiveLocations,
            ...$schemaTypeDirectiveLocations
        ];
    }
}
