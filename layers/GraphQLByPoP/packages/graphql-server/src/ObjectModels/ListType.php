<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class ListType extends AbstractWrappingType
{
    public function __construct(
        TypeInterface $wrappedType,
    ) {
        $this->id = sprintf(
            '[%s]',
            $wrappedType->getID()
        );
        parent::__construct($wrappedType);
    }

    public function getName(): string
    {
        return sprintf(
            '[%s]',
            $this->wrappedType->getName()
        );
    }

    public function getKind(): string
    {
        return TypeKinds::LIST;
    }
}
