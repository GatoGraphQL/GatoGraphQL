<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

class DateTimeScalarTypeResolver extends AbstractDateTimeScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'DateTime';
    }

    protected function getDateTimeFormat(): string
    {
        return 'Y-m-d H:i:s';
    }
}
