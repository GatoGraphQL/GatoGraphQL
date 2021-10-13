<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class ObjectTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected ObjectTypeResolverInterface $objectTypeResolver,
    ) {  
    }
    
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
        return $this->objectTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, []);
    }

    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
