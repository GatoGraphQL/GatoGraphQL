<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class ListType extends AbstractWrappingType
{
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

    public function getDescription(): ?string
    {
        return null;
    }
}
