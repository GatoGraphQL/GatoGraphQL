<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class ObjectType extends AbstractNamedType implements HasFieldsTypeInterface, HasInterfacesTypeInterface
{
    use HasFieldsTypeTrait;
    use HasInterfacesTypeTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initFields($fullSchemaDefinition, $schemaDefinitionPath, true);
    }

    public function getKind(): string
    {
        return TypeKinds::OBJECT;
    }
}
