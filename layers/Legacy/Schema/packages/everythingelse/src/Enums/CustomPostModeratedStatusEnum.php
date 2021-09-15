<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CustomPostModeratedStatusEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostModeratedStatus';
    }
    public function getValues(): array
    {
        return array_keys((new \GD_FormInput_ModeratedStatus())->getAllValues());
    }
}
