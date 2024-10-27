<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CustomPostsFilterCustomPostsByCategoriesInputObjectTypeResolver extends AbstractFilterCustomPostsByCategoriesInputObjectTypeResolver
{
    private ?CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getCategoryTaxonomyEnumStringScalarTypeResolver(): CategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->categoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
            $categoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->categoryTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FilterCustomPostsByCategoriesInput';
    }

    protected function getCategoryTaxonomyFilterInput(): InputTypeResolverInterface
    {
        return $this->getCategoryTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getCategoryTaxonomyFilterDefaultValue(): mixed
    {
        return null;
    }
}
