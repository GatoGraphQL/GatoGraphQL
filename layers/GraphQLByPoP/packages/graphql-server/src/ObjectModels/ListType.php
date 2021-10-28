<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;

class ListType extends AbstractWrappingType
{
    public function __construct(
        TypeInterface $wrappedType,
    ) {
        $this->id = GraphQLSchemaHelpers::getListTypeName($wrappedType->getID());
        parent::__construct($wrappedType);
    }

    public function getName(): string
    {
        return GraphQLSchemaHelpers::getListTypeName($this->wrappedType->getName());
    }

    public function getKind(): string
    {
        return TypeKinds::LIST;
    }
}
