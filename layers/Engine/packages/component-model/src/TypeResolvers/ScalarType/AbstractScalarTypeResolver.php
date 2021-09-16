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

    protected function validateIsNotArrayOrObject(mixed $inputValue): ?Error
    {
        // Fail if passing an array for unsupporting types
        if (is_array($inputValue) || is_object($inputValue)) {
            $entity = is_array($inputValue) ? 'array' : 'object';
            return new Error(
                sprintf('%s-cast', $entity),
                sprintf(
                    $this->translationAPI->__('An %s cannot be casted to type \'%s\'', 'component-model'),
                    $entity,
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return null;
    }
}
