<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeTrait;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface;

class ObjectType extends AbstractType implements HasFieldsTypeInterface, HasInterfacesTypeInterface
{
    use HasFieldsTypeTrait, HasInterfacesTypeTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);

        $this->initFields($fullSchemaDefinition, $schemaDefinitionPath, true, true);
        $this->initInterfaces($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function initializeTypeDependencies(): void
    {
        $this->initializeFieldTypeDependencies();
    }

    public function getKind(): string
    {
        return TypeKinds::OBJECT;
    }
}
