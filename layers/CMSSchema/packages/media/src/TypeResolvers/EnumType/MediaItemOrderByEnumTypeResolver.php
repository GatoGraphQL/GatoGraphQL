<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Media\Constants\MediaItemOrderBy;

class MediaItemOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'MediaItemOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            MediaItemOrderBy::ID,
            MediaItemOrderBy::TITLE,
            MediaItemOrderBy::DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            MediaItemOrderBy::ID => $this->__('Order by ID', 'media'),
            MediaItemOrderBy::TITLE => $this->__('Order by title', 'media'),
            MediaItemOrderBy::DATE => $this->__('Order by date', 'media'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
