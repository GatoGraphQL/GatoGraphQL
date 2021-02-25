<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;

class TypeKindEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'TypeKind';
    }
    public function getValues(): array
    {
        return [
            TypeKinds::SCALAR,
            TypeKinds::OBJECT,
            TypeKinds::INTERFACE,
            TypeKinds::UNION,
            TypeKinds::ENUM,
            TypeKinds::INPUT_OBJECT,
            TypeKinds::LIST,
            TypeKinds::NON_NULL,
        ];
    }
}
