<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class InterfaceType extends AbstractNamedType implements HasFieldsTypeInterface, HasPossibleTypesTypeInterface, HasInterfacesTypeInterface
{
    use HasFieldsTypeTrait;
    use HasPossibleTypesTypeTrait;
    use HasInterfacesTypeTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initFields($fullSchemaDefinition, $schemaDefinitionPath);
    }

    public function getKind(): string
    {
        return TypeKinds::INTERFACE;
    }
}
