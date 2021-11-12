<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTime;
use DateTimeInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use stdClass;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
abstract class AbstractDateTimeScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->getTranslationAPI()->__('%s scalar. It follows the ISO 8601 specification, with format "%s")', 'schema-commons'),
            $this->getTypeName(),
            $this->getDateTimeFormat()
        );
    }

    public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if ($error = $this->validateIsString($inputValue)) {
            return $error;
        }

        /**
         * Validate the format
         *
         * @see https://stackoverflow.com/a/13194398
         */
        $format = $this->getDateTimeFormat();
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

    abstract protected function getDateTimeFormat(): string;

    public function serialize(string|int|float|bool|object $scalarValue): string|int|float|bool|array
    {
        /** @var DateTimeInterface $scalarValue */
        return $scalarValue->format($this->getDateTimeFormat());
    }
}
