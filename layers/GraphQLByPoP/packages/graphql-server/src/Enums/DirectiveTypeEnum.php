<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use PoP\ComponentModel\Directives\DirectiveTypes;

class DirectiveTypeEnum extends AbstractEnum
{
    public const NAME = 'DirectiveType';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return array_map(
            'strtoupper',
            $this->getCoreValues()
        );
    }
    public function getCoreValues(): ?array
    {
        return [
            DirectiveTypes::QUERY,
            DirectiveTypes::SCHEMA,
        ];
    }
}
