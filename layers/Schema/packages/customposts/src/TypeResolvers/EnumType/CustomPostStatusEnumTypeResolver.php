<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\CustomPosts\Enums\CustomPostStatus;

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
            CustomPostStatus::PUBLISH => $this->getTranslationAPI()->__('Published content', 'customposts'),
            CustomPostStatus::PENDING => $this->getTranslationAPI()->__('Pending content', 'customposts'),
            CustomPostStatus::DRAFT => $this->getTranslationAPI()->__('Draft content', 'customposts'),
            CustomPostStatus::TRASH => $this->getTranslationAPI()->__('Trashed content', 'customposts'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
