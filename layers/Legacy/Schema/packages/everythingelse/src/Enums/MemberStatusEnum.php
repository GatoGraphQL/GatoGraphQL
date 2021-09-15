<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class MemberStatusEnum extends AbstractEnum
{
    public function getTypeName(): string
    {
        return 'MemberStatus';
    }
    public function getValues(): array
    {
        return array_keys((new \GD_URE_FormInput_MultiMemberStatus())->getAllValues());
    }
}
