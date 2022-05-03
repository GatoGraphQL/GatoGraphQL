<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\TypeResolvers\EnumType;

use GD_FormInput_ModeratedStatus;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class CustomPostModeratedStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostModeratedStatus';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys((new GD_FormInput_ModeratedStatus())->getAllValues());
    }
}
