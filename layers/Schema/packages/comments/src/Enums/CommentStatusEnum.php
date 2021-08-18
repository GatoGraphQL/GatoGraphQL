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
}
