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
            CommentOrderBy::CONTENT,
            CommentOrderBy::PARENT,
            CommentOrderBy::CUSTOM_POST,
            CommentOrderBy::TYPE,
            CommentOrderBy::STATUS,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'comments'),
            CommentOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'comments'),
            CommentOrderBy::CONTENT => $this->getTranslationAPI()->__('Order by content', 'comments'),
            CommentOrderBy::PARENT => $this->getTranslationAPI()->__('Order by parent comment', 'comments'),
            CommentOrderBy::CUSTOM_POST => $this->getTranslationAPI()->__('Order by ID of the custom post', 'comments'),
            CommentOrderBy::TYPE => $this->getTranslationAPI()->__('Order by type', 'comments'),
            CommentOrderBy::STATUS => $this->getTranslationAPI()->__('Order by status (approved or not)', 'comments'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
