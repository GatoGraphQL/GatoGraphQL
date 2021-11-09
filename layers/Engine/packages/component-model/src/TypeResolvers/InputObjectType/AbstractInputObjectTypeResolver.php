<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
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

    public function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        return $inputValue;
    }
}
