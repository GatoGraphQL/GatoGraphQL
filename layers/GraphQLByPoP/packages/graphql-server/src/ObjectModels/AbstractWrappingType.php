<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractWrappingType extends AbstractSchemaDefinitionReferenceObject implements WrappingTypeInterface
{
    public function __construct(
        protected TypeInterface $wrappedType,
    ) {
        parent::__construct();
    }

    public function getWrappedType(): TypeInterface
    {
        return $this->wrappedType;
    }

    public function getWrappedTypeID(): string
    {
        return $this->wrappedType->getID();
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
