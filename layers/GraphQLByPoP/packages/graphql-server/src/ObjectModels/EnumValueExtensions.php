<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class EnumValueExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function isAdminElement(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_ADMIN_ELEMENT];
    }
}
