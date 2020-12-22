<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface;

class InterfaceType extends AbstractType implements HasFieldsTypeInterface, HasPossibleTypesTypeInterface, HasInterfacesTypeInterface
{
    use HasFieldsTypeTrait, HasPossibleTypesTypeTrait, HasInterfacesTypeTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);

        $this->initFields($fullSchemaDefinition, $schemaDefinitionPath, false, false);
        $this->initInterfaces($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function initializeTypeDependencies(): void
    {
        $this->initPossibleTypes();
        $this->initializeFieldTypeDependencies();
    }

    public function getKind(): string
    {
        return TypeKinds::INTERFACE;
    }
}
