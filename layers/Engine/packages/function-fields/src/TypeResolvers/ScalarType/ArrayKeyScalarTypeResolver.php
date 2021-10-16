<?php

declare(strict_types=1);

namespace PoP\FunctionFields\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

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

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if ($error = $this->validateIsNotStdClass($inputValue)) {
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
