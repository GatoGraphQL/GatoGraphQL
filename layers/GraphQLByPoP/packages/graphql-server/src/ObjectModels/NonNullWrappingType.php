<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;

class NonNullWrappingType extends AbstractWrappingType
{
    public function __construct(
        TypeInterface $wrappedType,
    ) {
        $this->id = GraphQLSchemaHelpers::getNonNullableOrMandatoryTypeName($wrappedType->getID());
        parent::__construct($wrappedType);
    }

    public function getName(): string
    {
        return GraphQLSchemaHelpers::getNonNullableOrMandatoryTypeName($this->wrappedType->getName());
    }

    public function getKind(): string
    {
        return TypeKinds::NON_NULL;
    }
}
