<?php

declare(strict_types=1);

namespace PoP\API\Enums;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Enums\AbstractEnum;

class SchemaFieldShapeEnum extends AbstractEnum
{
    public const NAME = 'SchemaOutputShape';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return [
            SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT,
            SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_NESTED,
        ];
    }
}
