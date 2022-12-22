<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class GenericCustomPostCategoriesFilterInputObjectTypeResolver extends AbstractCategoriesFilterInputObjectTypeResolver
{
    private ?CategoryTaxonomyEnumTypeResolver $categoryTaxonomyEnumTypeResolver = null;

    final public function setCategoryTaxonomyEnumTypeResolver(CategoryTaxonomyEnumTypeResolver $categoryTaxonomyEnumTypeResolver): void
    {
        $this->categoryTaxonomyEnumTypeResolver = $categoryTaxonomyEnumTypeResolver;
    }
    final protected function getCategoryTaxonomyEnumTypeResolver(): CategoryTaxonomyEnumTypeResolver
    {
        /** @var CategoryTaxonomyEnumTypeResolver */
        return $this->categoryTaxonomyEnumTypeResolver ??= $this->instanceManager->getInstance(CategoryTaxonomyEnumTypeResolver::class);
    }
    
    public function getTypeName(): string
    {
        return 'GenericCustomPostCategoriesFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter categories from a generic custom post', 'categories');
    }

    protected function getTaxonomyInputFieldTypeResolver(): ?InputTypeResolverInterface
    {
        return $this->getCategoryTaxonomyEnumTypeResolver();
    }
}
