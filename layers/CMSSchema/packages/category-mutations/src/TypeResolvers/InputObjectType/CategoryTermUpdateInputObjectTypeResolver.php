<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CategoryTermUpdateInputObjectTypeResolver extends AbstractCreateOrUpdateCategoryTermInputObjectTypeResolver implements UpdateCategoryTermInputObjectTypeResolverInterface
{
    use CategoryTermUpdateInputObjectTypeResolverTrait;

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
        return 'CategoryUpdateInput';
    }

    protected function getTaxonomyInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getCategoryTaxonomyEnumStringScalarTypeResolver();
    }

    protected function isTaxonomyInputFieldMandatory(): bool
    {
        return false;
    }

    protected function getTaxonomyInputFieldDefaultValue(): mixed
    {
        return null;
    }
}
