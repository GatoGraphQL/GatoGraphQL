<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;

class ListWrappingType extends AbstractWrappingType
{
    public function getName(): string
    {
        return GraphQLSchemaHelpers::getListTypeName($this->wrappedType->getName());
    }

    public function getID(): string
    {
        return GraphQLSchemaHelpers::getListTypeName($this->wrappedType->getID());
    }

    public function getKind(): string
    {
        return TypeKinds::LIST;
    }
}
