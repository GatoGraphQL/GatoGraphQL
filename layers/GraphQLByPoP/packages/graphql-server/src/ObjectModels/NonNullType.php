<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNestableType;

class NonNullType extends AbstractNestableType
{
    use NonDocumentableTypeTrait;

    public function getName(): string
    {
        return sprintf(
            '%s!',
            $this->nestedType->getName()
        );
    }

    public function getKind(): string
    {
        return TypeKinds::NON_NULL;
    }
}
