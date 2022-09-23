<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use PoP\ComponentModel\Directives\DirectiveLocations;

class DirectiveLocationEnumTypeResolver extends AbstractIntrospectionEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveLocation';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            /**
             * All the enums below are "Query Type",
             * also called ExecutableDirectiveLocation
             *
             * @see https://spec.graphql.org/draft/#ExecutableDirectiveLocation
             */
            DirectiveLocations::QUERY,
            DirectiveLocations::MUTATION,
            DirectiveLocations::SUBSCRIPTION,
            DirectiveLocations::FIELD,
            DirectiveLocations::FRAGMENT_DEFINITION,
            DirectiveLocations::FRAGMENT_SPREAD,
            DirectiveLocations::INLINE_FRAGMENT,
            DirectiveLocations::VARIABLE_DEFINITION,

            /**
             * All the enums below are "Schema Type",
             * also called TypeSystemDirectiveLocation
             *
             * @see https://spec.graphql.org/draft/#TypeSystemDirectiveLocation
             */
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
    }
}
