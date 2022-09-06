<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class EnumValueExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function isSensitiveDataElement(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT];
    }
}
