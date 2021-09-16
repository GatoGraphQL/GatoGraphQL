<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Built-in Scalar
 * 
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class IDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'ID';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }
        /**
         * Type ID in GraphQL spec: only String or Int allowed.
         * 
         * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
         */
        if (is_float($inputValue) || is_bool($inputValue)) {
            return new Error(
                'id-cast',
                $this->translationAPI->__('Type ID in GraphQL spec: only String or Int allowed', 'component-model')
            );
        }
        return $inputValue;
    }
}
