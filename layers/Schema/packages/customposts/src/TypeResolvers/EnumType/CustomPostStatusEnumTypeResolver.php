<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\CustomPosts\Types\Status;

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

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            Status::PUBLISHED => $this->translationAPI->__('Published content', 'customposts'),
            Status::PENDING => $this->translationAPI->__('Pending content', 'customposts'),
            Status::DRAFT => $this->translationAPI->__('Draft content', 'customposts'),
            Status::TRASH => $this->translationAPI->__('Trashed content', 'customposts'),
            default => parent::getEnumValueDeprecationMessage($enumValue),
        };
    }
}
