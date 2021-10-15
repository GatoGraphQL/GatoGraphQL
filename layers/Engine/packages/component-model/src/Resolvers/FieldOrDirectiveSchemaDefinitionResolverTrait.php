<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

trait FieldOrDirectiveSchemaDefinitionResolverTrait
{
    final public function getFieldOrDirectiveArgSchemaDefinition(
        string $argName,
        InputTypeResolverInterface $argInputTypeResolver,
        ?string $argDescription,
        mixed $argDefaultValue,
        int $argTypeModifiers,
        ?string $argDeprecationMessage,
    ): array {
        return $this->getSchemaDefinition(
            $argName,
            $argInputTypeResolver,
            $argDescription,
            $argDefaultValue,
            $argTypeModifiers,
            $argDeprecationMessage,
        );
    }

    final public function getFieldSchemaDefinition(
        string $fieldName,
        ConcreteTypeResolverInterface $fieldTypeResolver,
        ?string $fieldDescription,
        int $fieldTypeModifiers,
        ?string $fieldDeprecationMessage,
    ): array {
        return $this->getSchemaDefinition(
            $fieldName,
            $fieldTypeResolver,
            $fieldDescription,
            null,
            $fieldTypeModifiers,
            $fieldDeprecationMessage,
        );
    }

    final public function getSchemaDefinition(
        string $name,
        TypeResolverInterface $typeResolver,
        ?string $description,
        mixed $defaultValue,
        int $typeModifiers,
        ?string $deprecationMessage,
    ): array {
        $schemaDefinition = [
            SchemaDefinition::NAME => $name,
            SchemaDefinition::TYPE_RESOLVER => $typeResolver,
        ];
        if ($description !== null) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($defaultValue !== null) {
            $schemaDefinition[SchemaDefinition::DEFAULT_VALUE] = $defaultValue;
        }
        if ($deprecationMessage !== null) {
            $schemaDefinition[SchemaDefinition::DEPRECATED] = true;
            $schemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
        }
        $this->processSchemaDefinitionModifiers(
            $schemaDefinition,
            $typeModifiers
        );
        return $schemaDefinition;
    }

    /**
     * Use bitwise operators to extract the applied modifiers
     *
     * @see https://www.php.net/manual/en/language.operators.bitwise.php#91291
     */
    private function processSchemaDefinitionModifiers(
        array &$schemaDefinition,
        int $typeModifiers,
    ): void {
        // This value is valid for the field or directive arg
        if ($typeModifiers & SchemaTypeModifiers::MANDATORY) {
            $schemaDefinition[SchemaDefinition::MANDATORY] = true;
        }

        // This value is valid for the field return value
        if ($typeModifiers & SchemaTypeModifiers::NON_NULLABLE) {
            $schemaDefinition[SchemaDefinition::NON_NULLABLE] = true;
        }

        // The values below are valid both for field, and field/directive args
        // If setting the "array of arrays" flag, there's no need to set the "array" flag
        $isArrayOfArrays = $typeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
        if (
            $typeModifiers & SchemaTypeModifiers::IS_ARRAY
            || $isArrayOfArrays
        ) {
            $schemaDefinition[SchemaDefinition::IS_ARRAY] = true;
            if ($typeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) {
                $schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] = true;
            }
            if ($isArrayOfArrays) {
                $schemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] = true;
                if ($typeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) {
                    $schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] = true;
                }
            }
        }
    }
}
