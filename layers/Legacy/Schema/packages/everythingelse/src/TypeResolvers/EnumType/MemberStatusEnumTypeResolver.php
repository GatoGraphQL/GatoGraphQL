<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\TypeResolvers\EnumType;

use GD_URE_FormInput_MultiMemberStatus;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class MemberStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MemberStatus';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys((new GD_URE_FormInput_MultiMemberStatus())->getAllValues());
    }
}
