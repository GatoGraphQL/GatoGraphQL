<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasTypeSchemaDefinitionReferenceTrait
{
    use ResolveTypeSchemaDefinitionReferenceTrait;
    
    public function getTypeID(): string
    {
        return SchemaDefinitionHelpers::getID([
            SchemaDefinition::TYPES,
            $this->schemaDefinition[SchemaDefinition::TYPE_KIND],
            $this->schemaDefinition[SchemaDefinition::TYPE_NAME],
        ]);
    }
}
