<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\TypeKinds;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;

class EnumTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function __construct(
        protected EnumTypeResolverInterface $enumTypeResolver,
    ) {
        parent::__construct($enumTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::ENUM;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addEnumSchemaDefinition($schemaDefinition);

        return $schemaDefinition;
    }

    /**
     * Add the enum values in the schema: arrays of enum name,
     * description, deprecated and deprecation description
     */
    final protected function addEnumSchemaDefinition(array &$schemaDefinition): void
    {
        $enums = [];
        $enumValues = $this->enumTypeResolver->getConsolidatedEnumValues();
        foreach ($enumValues as $enumValue) {
            $enum = [
                SchemaDefinition::VALUE => $enumValue,
            ];
            if ($description = $this->enumTypeResolver->getConsolidatedEnumValueDescription($enumValue)) {
                $enum[SchemaDefinition::DESCRIPTION] = $description;
            }
            if ($deprecationMessage = $this->enumTypeResolver->getConsolidatedEnumValueDeprecationMessage($enumValue)) {
                $enum[SchemaDefinition::DEPRECATED] = true;
                $enum[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
            }
            $enums[$enumValue] = $enum;
        }
        $schemaDefinition[SchemaDefinition::ITEMS] = $enums;
    }
}
