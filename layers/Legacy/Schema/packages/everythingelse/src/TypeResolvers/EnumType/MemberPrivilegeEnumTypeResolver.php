<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\TypeResolvers\EnumType;

use GD_URE_FormInput_FilterMemberPrivileges;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class MemberPrivilegeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MemberPrivilege';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys((new GD_URE_FormInput_FilterMemberPrivileges())->getAllValues());
    }
}
