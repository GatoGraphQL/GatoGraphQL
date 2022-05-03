<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\TypeResolvers\EnumType;

use GD_URE_FormInput_FilterMemberTags;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class MemberTagEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MemberTag';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys((new GD_URE_FormInput_FilterMemberTags())->getAllValues());
    }
}
