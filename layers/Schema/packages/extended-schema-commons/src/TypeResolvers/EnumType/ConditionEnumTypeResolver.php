<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\ExtendedSchemaCommons\Enums\ConditionEnum;

class ConditionEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'ConditionEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            ConditionEnum::IS_NULL,
            ConditionEnum::IS_EMPTY,
            ConditionEnum::ALWAYS,
        ];
    }
}
