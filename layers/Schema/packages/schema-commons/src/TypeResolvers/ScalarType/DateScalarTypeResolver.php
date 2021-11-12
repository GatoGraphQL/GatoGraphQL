<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

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
}
