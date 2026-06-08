<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;

class CustomPostStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostStatusEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CustomPostStatus::PUBLISH,
            CustomPostStatus::PENDING,
            CustomPostStatus::DRAFT,
            CustomPostStatus::TRASH,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostStatus::PUBLISH => $this->__('Published content', 'gatographql'),
            CustomPostStatus::PENDING => $this->__('Pending content', 'gatographql'),
            CustomPostStatus::DRAFT => $this->__('Draft content', 'gatographql'),
            CustomPostStatus::TRASH => $this->__('Trashed content', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
