<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class InterfaceTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected InterfaceTypeResolverInterface $interfaceTypeResolver,
    ) {
        parent::__construct($interfaceTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_INTERFACE;
    }
    
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        
        return $schemaDefinition;
    }

    public function getAccessedTypeAndDirectiveResolvers(): array
    {
        return [];
    }
}
