<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoP\ComponentModel\Directives\DirectiveTypes;

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

    /**
     * Output the enum value in UPPERCASE
     */
    public function getOutputEnumValueCallable(): ?callable
    {
        return 'strtoupper';
    }
}
