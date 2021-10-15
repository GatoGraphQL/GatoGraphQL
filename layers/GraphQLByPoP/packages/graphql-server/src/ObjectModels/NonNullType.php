<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class NonNullType extends AbstractWrappingType
{
    public function getName(): string
    {
        return sprintf(
            '%s!',
            $this->wrappedType->getName()
        );
    }

    public function getKind(): string
    {
        return TypeKinds::NON_NULL;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
