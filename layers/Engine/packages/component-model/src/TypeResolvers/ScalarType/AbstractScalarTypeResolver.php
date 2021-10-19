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

    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        /**
         * Convert stdClass to array, and apply recursively
         * (i.e. if some stdClass property is stdClass or object)
         */
        if ($scalarValue instanceof stdClass) {
            return array_map(
                function (mixed $scalarValueArrayElem): string|int|float|bool|array|null {
                    if ($scalarValueArrayElem === null) {
                        return null;
                    }
                    if (is_array($scalarValueArrayElem)) {
                        // Convert from array to stdClass
                        $scalarValueArrayElem = (object) $scalarValueArrayElem;
                    }
                    return $this->serialize($scalarValueArrayElem);
                },
                (array) $scalarValue
            );
        }
        // Convert object to string
        if (is_object($scalarValue)) {
            return $scalarValue->__serialize();
        }
        // Return as is
        return $scalarValue;
    }

    final protected function getError(string $message): Error
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
                    $this->translationAPI->__('An object cannot be casted to type \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return null;
    }

    final protected function validateFilterVar(mixed $inputValue, int $filter): ?Error
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

    final protected function validateIsString(string|int|float|bool|stdClass $inputValue): ?Error
    {
        if (!is_string($inputValue)) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Type \'%s\' must be provided as a string', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return null;
    }
}
