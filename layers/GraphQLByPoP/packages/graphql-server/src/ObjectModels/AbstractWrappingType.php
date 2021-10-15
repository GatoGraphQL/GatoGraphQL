<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractWrappingType extends AbstractType implements WrappingTypeInterface
{
    public function __construct(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        protected TypeInterface $nestedType,
    ) {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function getWrappedType(): TypeInterface
    {
        return $this->nestedType;
    }
    public function getWrappedTypeID(): string
    {
        return $this->nestedType->getID();
    }
}
