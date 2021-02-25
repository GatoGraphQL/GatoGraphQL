<?php

declare(strict_types=1);

namespace PoP\Engine\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class FieldFeedbackTargetEnum extends AbstractEnum
{
    public const DB = 'db';
    public const SCHEMA = 'schema';

    protected function getEnumName(): string
    {
        return 'FieldFeedbackTarget';
    }
    public function getValues(): array
    {
        return [
            self::DB,
            self::SCHEMA,
        ];
    }
}
