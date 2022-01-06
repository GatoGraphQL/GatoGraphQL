<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class PhoneNumberScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'PhoneNumber';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Phone number scalar, such as +1-212-555-0149', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc3966#section-5.1';
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        if (\preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $inputValue) !== 1) {
            return $this->getError(
                sprintf(
                    $this->__('The format for type \'%s\' is not right: it must be satisfied via regex /(\+{1}[0-9]{1,3}[0-9]{8,9})/', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }
        return $inputValue;
    }
}
