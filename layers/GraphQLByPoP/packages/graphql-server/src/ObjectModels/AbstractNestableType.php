<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;

abstract class AbstractNestableType extends AbstractType
{
    protected AbstractType $nestedType;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireAbstractNestableType(
        array &$fullSchemaDefinition,
        array $schemaDefinitionPath,
        AbstractType $nestedType,
        array $customDefinition = []
    ) {
        $this->nestedType = $nestedType;
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
