<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver as UpstreamCustomPostStatusEnumTypeResolver;

/**
 * Add the additional "WordPress"-specific statuses
 */
class CustomPostStatusEnumTypeResolver extends UpstreamCustomPostStatusEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            ...parent::getEnumValues(),
            CustomPostStatus::FUTURE,
            CustomPostStatus::PRIVATE,
            CustomPostStatus::INHERIT,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostStatus::FUTURE => $this->__('Future content', 'customposts'),
            CustomPostStatus::PRIVATE => $this->__('Private content', 'customposts'),
            CustomPostStatus::INHERIT => $this->__('Inherit content', 'customposts'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
