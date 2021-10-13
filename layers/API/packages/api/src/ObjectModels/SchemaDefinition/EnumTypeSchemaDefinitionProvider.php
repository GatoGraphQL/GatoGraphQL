<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;

class EnumTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function getType(): string
    {
        return SchemaDefinition::TYPE_ENUM;
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
