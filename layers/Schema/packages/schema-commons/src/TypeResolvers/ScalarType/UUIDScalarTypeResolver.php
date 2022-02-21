<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class UUIDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'UUID';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('UUID (universally unique identifier) scalar, such as 25770975-0c3d-4ff0-ba27-a0f98fe9b052', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc4122';
    }

    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): string|int|float|bool|object {
        $this->validateIsString($inputValue, $schemaInputValidationFeedbackStore);
        if ($schemaInputValidationFeedbackStore->getErrors() !== []) {
            return null;
        }

        if (\preg_match('/^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', $inputValue) !== 1) {
            return $this->getError(
                sprintf(
                    $this->__('The format for type \'%s\' is not right: it must be satisfied via regex /^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
