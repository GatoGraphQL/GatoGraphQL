<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UnionTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected UnionTypeResolverInterface $unionTypeResolver,
    ) {
        parent::__construct($unionTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_UNION;
    }
    
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        
        return $schemaDefinition;
    }

    public function getAccessedTypeResolvers(): array
    {
        return [];
    }
}
