<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;

class EnumTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected EnumTypeResolverInterface $enumTypeResolver,
    ) {
        parent::__construct($enumTypeResolver);
    }
    
    public function getType(): string
    {
        return SchemaDefinition::TYPE_ENUM;
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
