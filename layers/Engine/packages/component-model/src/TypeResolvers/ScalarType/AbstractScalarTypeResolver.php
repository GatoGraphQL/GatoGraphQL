<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements ScalarTypeResolverInterface
{
    /**
     * By default, the value is serialized as is
     */
    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        return $scalarValue;
    }

    protected function getError(string $message): Error
    {
        return new Error(
            sprintf('%s-cast', $this->getTypeName()),
            $message
        );
    }

    protected function getDefaultErrorMessage(mixed $inputValue): string
    {
        return sprintf(
            $this->translationAPI->__('Cannot cast value \'%s\' for type \'%s\'', 'component-model'),
            $inputValue,
            $this->getMaybeNamespacedTypeName(),
        );
    }

    protected function validateIsNotArrayOrObject(mixed $inputValue): ?Error
    {
        // Fail if passing an array for unsupporting types
        if (is_array($inputValue) || is_object($inputValue)) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('An %s cannot be casted to type \'%s\'', 'component-model'),
                    is_array($inputValue) ? 'array' : 'object',
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return null;
    }

    protected function validateFilterVar(mixed $inputValue, int $filter): ?Error
    {
        $valid = filter_var($inputValue, $filter);
        if ($valid === false) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('The format for \'%s\' is not right for type \'%s\'', 'component-model'),
                    $inputValue,
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return null;
    }
}
