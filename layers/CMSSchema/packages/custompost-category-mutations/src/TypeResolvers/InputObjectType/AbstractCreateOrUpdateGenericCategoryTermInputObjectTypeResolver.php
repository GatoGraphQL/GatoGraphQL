<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCreateOrUpdateGenericCategoryTermInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateGenericCategoryTermInputObjectTypeResolverInterface, CreateGenericCategoryTermInputObjectTypeResolverInterface
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

    protected function getTaxonomyInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getCategoryTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getTaxonomyInputFieldDefaultValue(): mixed
    {
        return null;
    }
}
