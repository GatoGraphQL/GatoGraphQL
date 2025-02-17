<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTimeInterface;

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

    /**
     * @return string[]
     */
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
