<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use stdClass;

abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements ScalarTypeResolverInterface
{
    public function getSpecifiedByURL(): ?string
    {
        return null;
    }

    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        // Convert stdClass to array
        if ($scalarValue instanceof stdClass) {
            return (array) $scalarValue;
        }
        // Convert object to string
        if (is_object($scalarValue)) {
            return $scalarValue->__serialize();
        }
        // Return as is
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

    final protected function validateIsNotStdClass(string|int|float|bool|stdClass $inputValue): ?Error
    {
        // Fail if passing an array for unsupporting types
        if ($inputValue instanceof stdClass) {
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
