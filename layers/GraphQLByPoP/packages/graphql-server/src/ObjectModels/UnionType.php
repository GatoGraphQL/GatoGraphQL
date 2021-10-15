<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class UnionType extends AbstractType implements HasPossibleTypesTypeInterface
{
    use HasPossibleTypesTypeTrait;

    public function getKind(): string
    {
        return TypeKinds::UNION;
    }
}
