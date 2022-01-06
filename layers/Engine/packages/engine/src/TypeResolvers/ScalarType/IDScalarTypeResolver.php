<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

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

    public function getTypeDescription(): ?string
    {
        return $this->__('The ID scalar type represents a unique identifier.', 'component-model');
    }

    /**
     * From the GraphQL spec, for section "ID > Input Coercion":
     *
     *   When expected as an input type, any string (such as "4")
     *   or integer (such as 4 or -4) input value should be coerced to ID
     *   as appropriate for the ID formats a given GraphQL service expects.
     *   Any other input value, including float input values (such as 4.0),
     *   must raise a request error indicating an incorrect type.
     *
     * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
            return $error;
        }
        /**
         * Type ID in GraphQL spec: only String or Int allowed.
         *
         * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
         */
        if (is_float($inputValue) || is_bool($inputValue)) {
            return $this->getError(
                sprintf(
                    $this->__('Only strings or integers are allowed for type \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
