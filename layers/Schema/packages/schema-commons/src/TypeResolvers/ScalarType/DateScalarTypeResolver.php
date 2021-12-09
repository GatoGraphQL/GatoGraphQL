<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

// @todo Replace with \DateTimeInterface. See: https://github.com/leoloso/PoP/issues/1282
use PoPSchema\SchemaCommons\Polyfill\PHP72\DateTimeInterface;

class DateScalarTypeResolver extends AbstractDateTimeScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'Date';
    }

    protected function getDateTimeFormat(): string
    {
        return 'Y-m-d';
    }

    protected function getDateTimeInputFormats(): array
    {
        return array_merge(
            parent::getDateTimeInputFormats(),
            [
                DateTimeInterface::ATOM,
            ]
        );
    }
}
