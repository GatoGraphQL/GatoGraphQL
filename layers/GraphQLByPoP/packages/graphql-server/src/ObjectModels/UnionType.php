<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface;

class UnionType extends AbstractType implements HasPossibleTypesTypeInterface
{
    use HasPossibleTypesTypeTrait;

    public function initializeTypeDependencies(): void
    {
        $this->initPossibleTypes();
    }

    public function getKind(): string
    {
        return TypeKinds::UNION;
    }
}
