<?php

declare(strict_types=1);

namespace PoPSchema\Comments\Enums;

use PoPSchema\Comments\Constants\CommentStatus;
use PoP\ComponentModel\Enums\AbstractEnum;

class CommentStatusEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'CommentStatus';
    }
    public function getValues(): array
    {
        return [
            CommentStatus::APPROVE,
            CommentStatus::HOLD,
            CommentStatus::SPAM,
            CommentStatus::TRASH,
        ];
    }
    public function getDescriptions(): array
    {
        return [
            CommentStatus::APPROVE => $this->translationAPI->__('Approved comment', 'comments'),
            CommentStatus::HOLD => $this->translationAPI->__('Onhold comment', 'comments'),
            CommentStatus::SPAM => $this->translationAPI->__('Spam comment', 'comments'),
            CommentStatus::TRASH => $this->translationAPI->__('Trashed comment', 'comments'),
        ];
    }
}
