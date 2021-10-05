<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

trait FieldOrDirectiveSchemaDefinitionResolverTrait
{
    use EnumTypeSchemaDefinitionResolverTrait;

    final public function getFieldOrDirectiveArgSchemaDefinition(
        string $argName,
        InputTypeResolverInterface $argInputTypeResolver,
        ?string $argDescription,
        mixed $argDefaultValue,
        ?int $argTypeModifiers,
    ): array {
        $schemaFieldOrDirectiveArgDefinition = [
            SchemaDefinition::ARGNAME_NAME => $argName,
            SchemaDefinition::ARGNAME_TYPE_RESOLVER => $argInputTypeResolver,
        ];
        if ($argInputTypeResolver instanceof EnumTypeResolverInterface) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_TYPE_NAME] = SchemaDefinition::TYPE_ENUM;
            /** @var EnumTypeResolverInterface */
            $argEnumTypeResolver = $argInputTypeResolver;
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaFieldOrDirectiveArgDefinition,
                $argEnumTypeResolver->getEnumValues(),
                $argEnumTypeResolver->getEnumValueDeprecationMessages(),
                $argEnumTypeResolver->getEnumValueDescriptions(),
                $argEnumTypeResolver->getMaybeNamespacedTypeName()
            );
        } else {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_TYPE_NAME] = $argInputTypeResolver->getMaybeNamespacedTypeName();
        }
        if ($argDescription !== null) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $argDescription;
        }
        if ($argDefaultValue !== null) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $argDefaultValue;
        }
        if ($argTypeModifiers !== null) {
            if ($argTypeModifiers & SchemaTypeModifiers::MANDATORY) {
                $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_MANDATORY] = true;
            }
            // If setting the "array of arrays" flag, there's no need to set the "array" flag
            $isArrayOfArrays = $argTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            if (
                $argTypeModifiers & SchemaTypeModifiers::IS_ARRAY
                || $isArrayOfArrays
            ) {
                $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] = true;
                if ($argTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) {
                    $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] = true;
                }
                if ($isArrayOfArrays) {
                    $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] = true;
                    if ($argTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) {
                        $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] = true;
                    }
                }
            }
        }
        return $schemaFieldOrDirectiveArgDefinition;
    }
}
