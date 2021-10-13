<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractTypeSchemaDefinitionProvider implements TypeSchemaDefinitionProviderInterface
{
    public function __construct(
        protected TypeResolverInterface $typeResolver,
    ) {  
    }
    
    public function getSchemaDefinition(): array
    {
        return [
            SchemaDefinition::NAME => $this->typeResolver->getMaybeNamespacedTypeName(),
            SchemaDefinition::NAMESPACED_NAME => $this->typeResolver->getNamespacedTypeName(),
            SchemaDefinition::ELEMENT_NAME => $this->typeResolver->getTypeName(),
        ];
    }
    
    public function getAccessedTypeAndDirectiveResolvers(): array
    {
        return [];
    }
}
