<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class MemberTagEnum extends AbstractEnum
{
    public const NAME = 'MemberTag';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return array_keys((new \GD_URE_FormInput_FilterMemberTags())->getAllValues());
    }
}
