<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Enums;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration;
use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;
use PoP\ComponentModel\Directives\DirectiveTypes;

class DirectiveTypeEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'DirectiveType';
    }
    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return array_map(
            'strtoupper',
            $this->getCoreValues()
        );
    }
    public function getCoreValues(): ?array
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
