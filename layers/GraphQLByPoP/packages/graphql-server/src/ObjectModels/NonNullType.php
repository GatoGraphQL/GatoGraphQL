<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class NonNullType extends AbstractNestableType
{
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

    public function getDescription(): ?string
    {
        return null;
    }
}
