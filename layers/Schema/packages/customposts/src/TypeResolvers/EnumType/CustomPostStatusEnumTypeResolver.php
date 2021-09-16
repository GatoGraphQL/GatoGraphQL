<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class CustomPostStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostStatus';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            Status::PUBLISHED,
            Status::PENDING,
            Status::DRAFT,
            Status::TRASH,
        ];
    }
}
