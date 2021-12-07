<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class FieldExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function isMutation(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::FIELD_IS_MUTATION];
    }

    public function isAdminElement(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_ADMIN_ELEMENT];
    }
}
