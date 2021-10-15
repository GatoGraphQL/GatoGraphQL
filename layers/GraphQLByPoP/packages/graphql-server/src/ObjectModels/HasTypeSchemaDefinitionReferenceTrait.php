<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasTypeSchemaDefinitionReferenceTrait
{
    /**
     * Append the GraphQL wrappers to the ID, to select any entity
     * of type NamedType or WrappingType
     */
    public function getTypeID(): string
    {
        $typeID = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($this->schemaDefinitionPath);
        return SchemaHelpers::getTypeToOutputInSchema(
            $typeID,
            $this->schemaDefinition[SchemaDefinition::NON_NULLABLE] ?? null,
            $this->schemaDefinition[SchemaDefinition::IS_ARRAY] ?? false,
            $this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
            $this->schemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false,
            $this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
        );
    }
}
