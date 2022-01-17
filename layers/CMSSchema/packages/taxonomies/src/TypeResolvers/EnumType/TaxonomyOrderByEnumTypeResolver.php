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
            TaxonomyOrderBy::NAME,
            TaxonomyOrderBy::SLUG,
            TaxonomyOrderBy::ID,
            TaxonomyOrderBy::DESCRIPTION,
            TaxonomyOrderBy::PARENT,
            TaxonomyOrderBy::COUNT,
            TaxonomyOrderBy::NONE,
            TaxonomyOrderBy::INCLUDE,
            TaxonomyOrderBy::SLUG__IN,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            TaxonomyOrderBy::NAME => $this->__('Order by name', 'taxonomies'),
            TaxonomyOrderBy::SLUG => $this->__('Order by slug', 'taxonomies'),
            TaxonomyOrderBy::ID => $this->__('Order by ID', 'taxonomies'),
            TaxonomyOrderBy::DESCRIPTION => $this->__('Order by description', 'taxonomies'),
            TaxonomyOrderBy::PARENT => $this->__('Order by parent', 'taxonomies'),
            TaxonomyOrderBy::COUNT => $this->__('Order by number of objects associated with the term', 'taxonomies'),
            TaxonomyOrderBy::NONE => $this->__('Order by none, i.e. omit the ordering', 'taxonomies'),
            TaxonomyOrderBy::INCLUDE => $this->__('Match the \'order\' of the $include param', 'taxonomies'),
            TaxonomyOrderBy::SLUG__IN => $this->__('Match the \'order\' of the $slug param', 'taxonomies'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
