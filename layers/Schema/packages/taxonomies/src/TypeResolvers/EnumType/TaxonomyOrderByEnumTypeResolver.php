<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Taxonomies\Constants\TaxonomyOrderBy;

class TaxonomyOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'TaxonomyOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            TaxonomyOrderBy::ID,
            TaxonomyOrderBy::TITLE,
            TaxonomyOrderBy::DATE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            TaxonomyOrderBy::ID => $this->getTranslationAPI()->__('Order by ID', 'taxonomies'),
            TaxonomyOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'taxonomies'),
            TaxonomyOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'taxonomies'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
