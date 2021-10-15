<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractNestableType extends AbstractType implements NestableTypeInterface
{
    public function __construct(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        protected TypeInterface $nestedType,
        array $customDefinition = []
    ) {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
    }
    public function getNestedType(): TypeInterface
    {
        return $this->nestedType;
    }
    public function getNestedTypeID(): string
    {
        return $this->nestedType->getID();
    }
}
