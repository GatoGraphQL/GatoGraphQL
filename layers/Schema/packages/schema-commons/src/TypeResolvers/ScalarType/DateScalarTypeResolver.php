<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTime;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class DateScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Date';
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        if ($error = $this->validateIsNotArrayOrObject($inputValue)) {
            return $error;
        }

        if (!is_string($inputValue)) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Type \'%s\' must be provided as a string', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                )
            );
        }

        /**
         * Validate that the format is 'Y-m-d'
         *
         * @see https://stackoverflow.com/a/13194398
         */
        $format = 'Y-m-d';
        $dt = DateTime::createFromFormat($format, $inputValue);
        if ($dt === false || array_sum($dt::getLastErrors())) {
            return $this->getError(
                sprintf(
                    $this->translationAPI->__('Type \'%s\' must be provided with format \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $format
                )
            );
        }
        return $inputValue;
    }
}
