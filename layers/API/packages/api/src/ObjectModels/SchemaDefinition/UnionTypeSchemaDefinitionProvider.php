<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;

class UnionTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function getType(): string
    {
        return SchemaDefinition::TYPE_UNION;
    }
    
    public function getSchemaDefinition(): array
    {
        return [];
    }

    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
