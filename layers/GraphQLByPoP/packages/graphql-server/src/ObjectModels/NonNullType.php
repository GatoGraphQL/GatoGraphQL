<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class NonNullType extends AbstractWrappingType
{
    public function __construct(
        TypeInterface $wrappedType,
    ) {
        parent::__construct($wrappedType);
        $this->id = sprintf(
            '%s!',
            $this->wrappedType->getID()
        );
    }

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
}
