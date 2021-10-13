<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractTypeSchemaDefinitionProvider implements TypeSchemaDefinitionProviderInterface
{
    public function __construct(
        protected TypeResolverInterface $typeResolver,
    ) {  
    }
    
    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
