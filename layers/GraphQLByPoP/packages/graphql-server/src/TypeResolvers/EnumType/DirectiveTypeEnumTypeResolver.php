<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class DirectiveTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveType';
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
}
