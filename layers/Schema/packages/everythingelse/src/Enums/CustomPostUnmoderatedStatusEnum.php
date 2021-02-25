<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class CustomPostUnmoderatedStatusEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'CustomPostUnmoderatedStatus';
    }
    public function getValues(): array
    {
        return array_keys((new \GD_FormInput_UnmoderatedStatus())->getAllValues());
    }
}
