<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Enums;

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\Enums\AbstractEnum;

class CustomPostStatusEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'CustomPostStatus';
    }
    public function getValues(): array
    {
        return [
            Status::PUBLISHED,
            Status::PENDING,
            Status::DRAFT,
            Status::TRASH,
        ];
    }
}
