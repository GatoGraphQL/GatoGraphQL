<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use stdClass;

/**
 * Special scalar type which is not coerced or validated.
 * In particular, it does not need to validate if it is an array or not,
 * as according to the applied WrappingType.
 *
 * This is to enable it to have an array as value, which is not
 * allowed by GraphQL unless the array is explicitly defined.
 *
 * For instance, type `DangerouslyNonSpecificScalar` could have values
 * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
 * these values by types `String` and `[String]`.
 */
class DangerouslyNonSpecificTypeTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'DangerouslyNonSpecificScalar';
    }

    /**
     * This method will never be called for DangerouslyNonSpecificScalar
     */
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object|null {
        return $inputValue;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Special scalar type which is not coerced or validated. In particular, it does not need to validate if it is an array or not, as GraphQL requires based on the applied WrappingType (such as `[String]`).', 'component-model');
    }
}
