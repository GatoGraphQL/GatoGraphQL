<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CustomPostUnmoderatedStatusEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostUnmoderatedStatus';
    }
    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return array_keys((new \GD_FormInput_UnmoderatedStatus())->getAllValues());
    }
}
