<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy;

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
            CustomPostOrderBy::ID => $this->__('Order by ID', 'gatographql'),
            CustomPostOrderBy::TITLE => $this->__('Order by title', 'gatographql'),
            CustomPostOrderBy::DATE => $this->__('Order by date', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
