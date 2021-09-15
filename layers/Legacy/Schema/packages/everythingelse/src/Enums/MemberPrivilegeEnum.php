<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class MemberPrivilegeEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MemberPrivilege';
    }
    /**
     * @return array<int|float|bool|string>
     */
    public function getValues(): array
    {
        return array_keys((new \GD_URE_FormInput_FilterMemberPrivileges())->getAllValues());
    }
}
