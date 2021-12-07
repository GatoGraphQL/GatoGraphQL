<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class DirectiveExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function getVersion(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::VERSION] ?? null;
    }
}
