<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\EnumType;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;
use PoP\Engine\Enums\FieldFeedbackTypeEnum;

class FieldFeedbackTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'FieldFeedbackType';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            FieldFeedbackTypeEnum::WARNING,
            FieldFeedbackTypeEnum::DEPRECATION,
            FieldFeedbackTypeEnum::NOTICE,
        ];
    }
}
