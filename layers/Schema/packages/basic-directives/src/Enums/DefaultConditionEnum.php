<?php

declare(strict_types=1);

namespace PoPSchema\BasicDirectives\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class DefaultConditionEnum extends AbstractEnum
{
    public const IS_NULL = 'IS_NULL';
    public const IS_EMPTY = 'IS_EMPTY';

    protected function getEnumName(): string
    {
        return 'DefaultCondition';
    }
    public function getValues(): array
    {
        return [
            self::IS_NULL,
            self::IS_EMPTY,
        ];
    }
}
