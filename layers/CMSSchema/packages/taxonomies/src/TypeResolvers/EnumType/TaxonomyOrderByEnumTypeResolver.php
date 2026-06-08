<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;

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
            TaxonomyOrderBy::NAME => $this->__('Order by name', 'gatographql'),
            TaxonomyOrderBy::SLUG => $this->__('Order by slug', 'gatographql'),
            TaxonomyOrderBy::ID => $this->__('Order by ID', 'gatographql'),
            TaxonomyOrderBy::DESCRIPTION => $this->__('Order by description', 'gatographql'),
            TaxonomyOrderBy::PARENT => $this->__('Order by parent', 'gatographql'),
            TaxonomyOrderBy::COUNT => $this->__('Order by number of objects associated with the term', 'gatographql'),
            TaxonomyOrderBy::NONE => $this->__('Order by none, i.e. omit the ordering', 'gatographql'),
            TaxonomyOrderBy::INCLUDE => $this->__('Match the \'order\' of the $include param', 'gatographql'),
            TaxonomyOrderBy::SLUG__IN => $this->__('Match the \'order\' of the $slug param', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
