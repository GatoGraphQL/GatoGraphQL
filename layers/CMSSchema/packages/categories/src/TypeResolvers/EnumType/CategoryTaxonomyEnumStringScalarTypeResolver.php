<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\EnumType;

class CategoryTaxonomyEnumStringScalarTypeResolver extends AbstractCategoryTaxonomyEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryTaxonomyEnumString';
    }

    protected function getRegisteredCustomPostCategoryTaxonomyNames(): ?array
    {
        return null;
    }
}
