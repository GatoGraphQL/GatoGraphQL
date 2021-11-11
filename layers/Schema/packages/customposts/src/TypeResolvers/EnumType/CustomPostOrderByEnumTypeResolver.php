<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Posts\Constants\PostOrderBy;

class CustomPostOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostOrderBy';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            PostOrderBy::ID,
            PostOrderBy::TITLE,
            PostOrderBy::DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            PostOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'schema-commons'),
            PostOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'schema-commons'),
            PostOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
