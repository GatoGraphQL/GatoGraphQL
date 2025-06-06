<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTimeInterface;

class DateTimeScalarTypeResolver extends AbstractDateTimeScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'DateTime';
    }

    protected function getDateTimeFormat(): string
    {
        return DateTimeInterface::ATOM;
    }
}
