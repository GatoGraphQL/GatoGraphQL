<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Media\Constants\MediaItemOrderBy;

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
            MediaItemOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'media'),
            MediaItemOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'media'),
            MediaItemOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'media'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
