<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations;

class DirectiveLocationEnum extends AbstractEnum
{
    public const NAME = 'DirectiveLocation';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return [
            DirectiveLocations::QUERY,
            DirectiveLocations::MUTATION,
            DirectiveLocations::SUBSCRIPTION,
            DirectiveLocations::FIELD,
            DirectiveLocations::FRAGMENT_DEFINITION,
            DirectiveLocations::FRAGMENT_SPREAD,
            DirectiveLocations::INLINE_FRAGMENT,
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
