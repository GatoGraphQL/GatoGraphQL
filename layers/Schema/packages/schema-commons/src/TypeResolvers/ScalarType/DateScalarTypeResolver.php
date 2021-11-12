<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTime;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

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

    public function getSpecifiedByURL(): ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc3339#section-5.6';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Date scalar. It follows the ISO 8601 specification, with format "Y-m-d" (representing "<YYYY>-<MM>-<DD>")', 'schema-commons');
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
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
                    $this->getTranslationAPI()->__('Type \'%s\' must be provided with format \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $format
                )
            );
        }
        return $dt;
    }
}
