<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\ErrorHandling\Error;
use stdClass;

/**
 * Special scalar type which is not coerced or validated.
 * In particular, it does not need to validate if it is an array or not,
 * as according to the applied WrappingType.
 *
 * This is to enable it to have an array as value, which is not
 * allowed by GraphQL unless the array is explicitly defined.
 *
 * For instance, type `DangerouslyDynamic` could have values
 * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
 * these values by types `String` and `[String]`.
 */
class DangerouslyDynamicScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'DangerouslyDynamic';
    }

    /**
     * This method will never be called
     */
    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        return $inputValue;
    }

    /**
     * Convert any contained stdClass to array
     */
    public function serialize(mixed $scalarValue): string|int|float|bool|array
    {
        if ($scalarValue instanceof stdClass || is_array($scalarValue)) {
            return json_decode(json_encode($scalarValue), true);
        }
        return parent::serialize($scalarValue);
    }
}
