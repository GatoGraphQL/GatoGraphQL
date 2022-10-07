<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class FieldExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function isGlobal(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::FIELD_IS_GLOBAL];
    }

    public function isMutation(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::FIELD_IS_MUTATION];
    }

    public function isSensitiveDataElement(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT];
    }
}
