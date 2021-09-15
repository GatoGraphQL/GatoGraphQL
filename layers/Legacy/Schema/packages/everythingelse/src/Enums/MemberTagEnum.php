<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class MemberTagEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MemberTag';
    }
    public function getValues(): array
    {
        return array_keys((new \GD_URE_FormInput_FilterMemberTags())->getAllValues());
    }
}
