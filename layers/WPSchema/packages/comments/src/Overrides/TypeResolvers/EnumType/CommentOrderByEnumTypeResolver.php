<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Overrides\TypeResolvers\EnumType;

use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver as UpstreamCommentOrderByEnumTypeResolver;
use PoPWPSchema\Comments\Constants\CommentOrderBy;

class CommentOrderByEnumTypeResolver extends UpstreamCommentOrderByEnumTypeResolver
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
        return array_merge(
            parent::getEnumValues(),
            [
                CommentOrderBy::AUTHOR_EMAIL,
                CommentOrderBy::AUTHOR_IP,
                CommentOrderBy::AUTHOR_URL,
                CommentOrderBy::KARMA,
                CommentOrderBy::NONE,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentOrderBy::AUTHOR_EMAIL => $this->__('Order by author email', 'comments'),
            CommentOrderBy::AUTHOR_IP => $this->__('Order by author IP', 'comments'),
            CommentOrderBy::AUTHOR_URL => $this->__('Order by author URL', 'comments'),
            CommentOrderBy::KARMA => $this->__('Order by karma', 'comments'),
            CommentOrderBy::NONE => $this->__('Skip ordering', 'comments'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
