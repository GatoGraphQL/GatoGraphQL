<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentStatus;

class CommentStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentStatusEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CommentStatus::APPROVE,
            CommentStatus::HOLD,
            CommentStatus::SPAM,
            CommentStatus::TRASH,
        ];
    }

    /**
     * Description for a specific enum value
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentStatus::APPROVE => $this->__('Approved comment', 'comments'),
            CommentStatus::HOLD => $this->__('Onhold comment', 'comments'),
            CommentStatus::SPAM => $this->__('Spam comment', 'comments'),
            CommentStatus::TRASH => $this->__('Trashed comment', 'comments'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
