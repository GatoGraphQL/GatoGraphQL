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
    public function getBackedEnumClass(): ?string
    {
        return ConditionEnum::class;
    }
}
