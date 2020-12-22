<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\NonDocumentableTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNestableType;

class ListType extends AbstractNestableType
{
    use NonDocumentableTypeTrait;

    public function getName(): string
    {
        return sprintf(
            '[%s]',
            $this->nestedType->getName()
        );
    }

    public function getKind(): string
    {
        return TypeKinds::LIST;
    }
}
