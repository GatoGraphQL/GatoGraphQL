<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionTypes;
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
        ?string $argDeprecationDescription,
    ): array {
        $schemaFieldOrDirectiveArgDefinition = [
            SchemaDefinition::NAME => $argName,
            SchemaDefinition::TYPE_RESOLVER => $argInputTypeResolver,
        ];
        if ($argInputTypeResolver instanceof EnumTypeResolverInterface) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::TYPE_NAME] = SchemaDefinitionTypes::TYPE_ENUM;
            /** @var EnumTypeResolverInterface */
            $argEnumTypeResolver = $argInputTypeResolver;
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaFieldOrDirectiveArgDefinition,
                $argEnumTypeResolver,
            );
        } else {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::TYPE_NAME] = $argInputTypeResolver->getMaybeNamespacedTypeName();
        }
        if ($argDescription !== null) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::DESCRIPTION] = $argDescription;
        }
        if ($argDefaultValue !== null) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::DEFAULT_VALUE] = $argDefaultValue;
        }
        if ($argTypeModifiers !== null) {
            if ($argTypeModifiers & SchemaTypeModifiers::MANDATORY) {
                $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::MANDATORY] = true;
            }
            // If setting the "array of arrays" flag, there's no need to set the "array" flag
            $isArrayOfArrays = $argTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            if (
                $argTypeModifiers & SchemaTypeModifiers::IS_ARRAY
                || $isArrayOfArrays
            ) {
                $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::IS_ARRAY] = true;
                if ($argTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) {
                    $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] = true;
                }
                if ($isArrayOfArrays) {
                    $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] = true;
                    if ($argTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) {
                        $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] = true;
                    }
                }
            }
        }
        if ($argDeprecationDescription !== null) {
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::DEPRECATED] = $argDeprecationDescription;
            $schemaFieldOrDirectiveArgDefinition[SchemaDefinition::DEPRECATIONDESCRIPTION] = $argDeprecationDescription;
        }
        return $schemaFieldOrDirectiveArgDefinition;
    }
}
