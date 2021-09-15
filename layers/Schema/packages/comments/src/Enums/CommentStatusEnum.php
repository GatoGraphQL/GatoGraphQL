<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Enums;

use PoPSchema\Comments\Constants\CommentStatus;
use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CommentStatusEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentStatus';
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
     * Use the original values
     */
    public function getOutputEnumValueCallable(): ?callable
    {
        return null;
    }
    
    /**
     * Description for a specific enum value
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentStatus::APPROVE => $this->translationAPI->__('Approved comment', 'comments'),
            CommentStatus::HOLD => $this->translationAPI->__('Onhold comment', 'comments'),
            CommentStatus::SPAM => $this->translationAPI->__('Spam comment', 'comments'),
            CommentStatus::TRASH => $this->translationAPI->__('Trashed comment', 'comments'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
