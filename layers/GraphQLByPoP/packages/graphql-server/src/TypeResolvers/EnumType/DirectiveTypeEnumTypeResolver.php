<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use stdClass;

class DirectiveTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveTypeEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            [
                DirectiveTypes::QUERY,
                DirectiveTypes::SCHEMA,
            ],
            ComponentConfiguration::enableComposableDirectives() ? [
                DirectiveTypes::INDEXING,
            ] : [],
        );
    }

    /**
     * Convert the DirectiveType enum from UPPERCASE as input, to lowercase
     * as defined in DirectiveTypes.php
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        return parent::coerceValue(strtolower($inputValue));
    }

    /**
     * Convert back from lowercase to UPPERCASE
     */
    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        return strtoupper($scalarValue);
    }
}
