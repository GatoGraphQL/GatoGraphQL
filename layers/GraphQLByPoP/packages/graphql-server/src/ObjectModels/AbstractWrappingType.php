<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractWrappingType extends AbstractNamedType implements WrappingTypeInterface
{
    public function __construct(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        protected TypeInterface $wrappedType,
    ) {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function getWrappedType(): TypeInterface
    {
        return $this->wrappedType;
    }
    public function getWrappedTypeID(): string
    {
        return $this->wrappedType->getID();
    }
}
