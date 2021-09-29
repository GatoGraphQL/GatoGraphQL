<?php

declare(strict_types=1);

namespace PoP\FunctionFields\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class ArrayKeyScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'ArrayKey';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }
        /**
         * Only String or Int
         */
        if (is_float($inputValue) || is_bool($inputValue)) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Only strings or integers are allowed for type \'%s\'', 'schema-commons'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
