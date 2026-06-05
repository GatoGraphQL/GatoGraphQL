<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentOrderBy;

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
            CommentOrderBy::ID => $this->__('Order by ID', 'gatographql'),
            CommentOrderBy::DATE => $this->__('Order by date', 'gatographql'),
            CommentOrderBy::CONTENT => $this->__('Order by content', 'gatographql'),
            CommentOrderBy::PARENT => $this->__('Order by parent comment', 'gatographql'),
            CommentOrderBy::CUSTOM_POST => $this->__('Order by ID of the custom post', 'gatographql'),
            CommentOrderBy::TYPE => $this->__('Order by type', 'gatographql'),
            CommentOrderBy::STATUS => $this->__('Order by status (approved or not)', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
