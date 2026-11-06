<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Enums\CommentStatus;

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
            CommentStatus::APPROVE->value,
            CommentStatus::HOLD->value,
            CommentStatus::SPAM->value,
            CommentStatus::TRASH->value,
        ];
    }

    /**
     * Description for a specific enum value
     */
    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CommentStatus::APPROVE->value => $this->__('Approved comment', 'gatographql'),
            CommentStatus::HOLD->value => $this->__('Onhold comment', 'gatographql'),
            CommentStatus::SPAM->value => $this->__('Spam comment', 'gatographql'),
            CommentStatus::TRASH->value => $this->__('Trashed comment', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
