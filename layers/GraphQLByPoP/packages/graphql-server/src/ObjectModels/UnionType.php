<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class UnionType extends AbstractNamedType implements HasPossibleTypesTypeInterface
{
    use HasPossibleTypesTypeTrait;

    public function getKind(): string
    {
        return TypeKinds::UNION;
    }
}
