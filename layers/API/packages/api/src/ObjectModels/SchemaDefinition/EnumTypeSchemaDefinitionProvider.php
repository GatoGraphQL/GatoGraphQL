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
        $adminEnumValues = $this->enumTypeResolver->getConsolidatedAdminEnumValues();
        foreach ($enumValues as $enumValue) {
            $enumValueSchemaDefinition = [
                SchemaDefinition::VALUE => $enumValue,
            ];
            if ($description = $this->enumTypeResolver->getConsolidatedEnumValueDescription($enumValue)) {
                $enumValueSchemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
            }
            if ($deprecationMessage = $this->enumTypeResolver->getConsolidatedEnumValueDeprecationMessage($enumValue)) {
                $enumValueSchemaDefinition[SchemaDefinition::DEPRECATED] = true;
                $enumValueSchemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
            }
            if (in_array($enumValue, $adminEnumValues)) {
                $enumValueSchemaDefinition[SchemaDefinition::IS_ADMIN_ELEMENT] = true;
            }
            $enums[$enumValue] = $enumValueSchemaDefinition;
        }
        $schemaDefinition[SchemaDefinition::ITEMS] = $enums;
    }
}
