<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;

class ObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function getType(): string
    {
        return SchemaDefinition::TYPE_OBJECT;
    }
    
    public function getSchemaDefinition(): array
    {
        $stackMessages = [
            'processed' => [],
        ];
        $generalMessages = [
            'processed' => [],
        ];
        return $this->typeResolver->getSchemaDefinition($stackMessages, $generalMessages, []);
    }

    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
