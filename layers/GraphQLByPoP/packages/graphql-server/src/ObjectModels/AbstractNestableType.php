<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;

abstract class AbstractNestableType extends AbstractType
{
    public function __construct(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        protected AbstractType $nestedType,
        array $customDefinition = []
    ) {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
    }
    public function getNestedType(): AbstractType
    {
        return $this->nestedType;
    }
    public function getNestedTypeID(): string
    {
        return $this->nestedType->getID();
    }
}
