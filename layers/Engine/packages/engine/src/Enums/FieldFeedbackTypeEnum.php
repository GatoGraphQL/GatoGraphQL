<?php

declare(strict_types=1);

namespace PoP\Engine\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class FieldFeedbackTypeEnum extends AbstractEnum
{
    public const NAME = 'FieldFeedbackType';

    public const WARNING = 'warning';
    public const DEPRECATION = 'deprecation';
    public const NOTICE = 'notice';

    protected function getEnumName(): string
    {
        return self::NAME;
    }
    public function getValues(): array
    {
        return [
            self::WARNING,
            self::DEPRECATION,
            self::NOTICE,
        ];
    }
}
