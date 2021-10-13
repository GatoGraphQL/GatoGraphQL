<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;

class EnumTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    use EnumTypeSchemaDefinitionResolverTrait;
    
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

        $this->doAddSchemaDefinitionEnumValuesForField(
            $schemaDefinition,
            $this->enumTypeResolver
        );

        return $schemaDefinition;
    }
}
