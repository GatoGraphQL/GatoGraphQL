<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class ObjectScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Object';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if (!(is_array($inputValue) || is_object($inputValue))) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Cannot cast value \'%s\' to type \'%s\'', 'component-model'),
                    json_decode($inputValue),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
