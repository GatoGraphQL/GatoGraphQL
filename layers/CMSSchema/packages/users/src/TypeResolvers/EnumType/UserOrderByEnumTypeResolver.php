<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Users\Constants\UserOrderBy;

class UserOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            UserOrderBy::ID,
            UserOrderBy::NAME,
            UserOrderBy::USERNAME,
            UserOrderBy::DISPLAY_NAME,
            UserOrderBy::REGISTRATION_DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            UserOrderBy::ID => $this->__('Order by ID', 'users'),
            UserOrderBy::NAME => $this->__('Order by name', 'users'),
            UserOrderBy::USERNAME => $this->__('Order by username (login name)', 'users'),
            UserOrderBy::DISPLAY_NAME => $this->__('Order by the user display name', 'users'),
            UserOrderBy::REGISTRATION_DATE => $this->__('Order by registration date', 'users'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
