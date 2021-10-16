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

    public function serialize(string|int|float|bool|stdClass $scalarValue): string|int|float|bool|array
    {
        // Convert stdClass to array, recursively (i.e. if the stdClass contains stdClass)
        if ($scalarValue instanceof stdClass) {
            return json_decode(json_encode($scalarValue), true);
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
}
