<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\TypeResolvers\EnumType;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CustomPostUnmoderatedStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostUnmoderatedStatus';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys((new \GD_FormInput_UnmoderatedStatus())->getAllValues());
    }
}
