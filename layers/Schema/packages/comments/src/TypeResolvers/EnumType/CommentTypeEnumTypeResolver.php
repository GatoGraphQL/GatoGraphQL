<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\EnumType;

use PoPSchema\Comments\Constants\CommentTypes;
use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CommentTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentType';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CommentTypes::COMMENT,
            CommentTypes::TRACKBACK,
            CommentTypes::PINGBACK,
        ];
    }

    /**
     * Use the original values
     */
    public function getOutputEnumValueCallable(): ?callable
    {
        return null;
    }
}
