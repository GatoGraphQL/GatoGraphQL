<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class NonNullWrappingType extends AbstractWrappingType
{
    public function getName(): string
    {
        return sprintf(
            '%s!',
            $this->wrappedType->getName()
        );
    }

    public function getID(): string
    {
        return sprintf(
            '%s!',
            $this->wrappedType->getID()
        );
    }

    public function getKind(): string
    {
        return TypeKinds::NON_NULL;
    }
}
