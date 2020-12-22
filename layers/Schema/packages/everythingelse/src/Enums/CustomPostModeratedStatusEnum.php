<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class CustomPostModeratedStatusEnum extends AbstractEnum
{
    public const NAME = 'CustomPostModeratedStatus';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return array_keys((new \GD_FormInput_ModeratedStatus())->getAllValues());
    }
}
