<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\CustomPosts\Constants\CustomPostOrderBy;

class CustomPostOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CustomPostOrderBy::ID,
            CustomPostOrderBy::TITLE,
            CustomPostOrderBy::DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'users'),
            CustomPostOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'users'),
            CustomPostOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'users'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
