<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use stdClass;

abstract class AbstractInputObjectTypeResolver extends AbstractTypeResolver implements InputObjectTypeResolverInterface
{
    public function getInputObjectFieldDescription(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDeprecationMessage(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDefaultValue(string $inputObjectFieldName): mixed
    {
        return null;
    }
    public function getInputObjectFieldTypeModifiers(string $inputObjectFieldName): int
    {
        return SchemaTypeModifiers::NONE;
    }

    final public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if (!($inputValue instanceof stdClass)) {
            return $this->getError(
                sprintf(
                    $this->getTranslationAPI()->__('Input object of type \'%s\' cannot be casted from input value \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $inputValue
                )
            );
        }
        return $this->coerceInputObjectValue($inputValue);
    }

    /**
     * Iterate all the properties of the inputValue, and coerce each
     */
    public function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        $coercedInputObjectValue = new stdClass;
        /** @var Error[] */
        $errors = [];
        $inputObjectFieldNameTypeResolvers = $this->getInputObjectFieldNameTypeResolvers();
        foreach ($inputValue as $fieldName => $inputTypeResolver) {
            // Check that the property exists
            if (!isset($inputObjectFieldNameTypeResolvers[$fieldName])) {
                $errors[] = sprintf(
                    $this->getTranslationAPI()->__('There is no property \'%s\' in input object \'%s\''),
                    $fieldName,
                    $this->getMaybeNamespacedTypeName()
                );
                continue;
            }
            // $inputObjectFieldTypeModifiers = $this->getInputObjectFieldTypeModifiers($fieldName);
            // Coerce the value using the property's typeResolver
            $coercedInputPropertyValue = $inputTypeResolver->coerceValue($inputValue->$fieldName);
            if (GeneralUtils::isError($coercedInputPropertyValue)) {
                $errors[] = $coercedInputPropertyValue;
                continue;
            }
            // The property is valid, add to the resulting InputObject
            $coercedInputObjectValue->$fieldName = $coercedInputPropertyValue;
        }
        // Check that all mandatory properties have been provided

        // If there was any error, return it
        if ($errors) {
            return $this->getError(
                sprintf(
                    $this->getTranslationAPI()->__('Input object of type \'%s\' cannot be casted due to nested errors', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                ),
                $errors
            );
        }
        
        // Add all missing properties which have a default value
        return $coercedInputObjectValue;
    }
}
