<?php

declare(strict_types=1);

namespace PoP\Engine\TypeResolvers\EnumType;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;

class FieldFeedbackTargetEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'FieldFeedbackTarget';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            FieldFeedbackTargetEnum::DB,
            FieldFeedbackTargetEnum::SCHEMA,
        ];
    }
}
