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
        parent::__construct($objectTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_OBJECT;
    }
    
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        // $stackMessages = [
        //     'processed' => [],
        // ];
        // $generalMessages = [
        //     'processed' => [],
        // ];
        // return $this->objectTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, []);
        
        return $schemaDefinition;
    }

    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
