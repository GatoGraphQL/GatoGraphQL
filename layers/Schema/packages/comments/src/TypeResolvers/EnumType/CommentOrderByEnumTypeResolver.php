<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Comments\Constants\CommentOrderBy;

class CommentOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CommentOrderBy::ID,
            CommentOrderBy::DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'comments'),
            CommentOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'comments'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
