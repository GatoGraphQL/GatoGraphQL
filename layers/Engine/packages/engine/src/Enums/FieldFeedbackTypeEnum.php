<?php

declare(strict_types=1);

namespace PoP\Engine\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class FieldFeedbackTypeEnum extends AbstractEnumTypeResolver
{
    public const WARNING = 'warning';
    public const DEPRECATION = 'deprecation';
    public const NOTICE = 'notice';

    public function getTypeName(): string
    {
        return 'FieldFeedbackType';
    }
    /**
     * @return array<int|float|bool|string>
     */
    public function getValues(): array
    {
        return [
            self::WARNING,
            self::DEPRECATION,
            self::NOTICE,
        ];
    }
}
