<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractFilterCustomPostsByCategoriesInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\EnumType\PostCategoryTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class PostsFilterCustomPostsByCategoriesInputObjectTypeResolver extends AbstractFilterCustomPostsByCategoriesInputObjectTypeResolver
{
    private ?PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver = null;

    final public function setPostCategoryTaxonomyEnumStringScalarTypeResolver(PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver): void
    {
        $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getPostCategoryTaxonomyEnumStringScalarTypeResolver(): PostCategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->postCategoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var PostCategoryTaxonomyEnumStringScalarTypeResolver */
            $postCategoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(PostCategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->postCategoryTaxonomyEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FilterPostsByCategoriesInput';
    }

    protected function getCategoryTaxonomyFilterInput(): InputTypeResolverInterface
    {
        return $this->getPostCategoryTaxonomyEnumStringScalarTypeResolver();
    }

    protected function getCategoryTaxonomyFilterDefaultValue(): mixed
    {
        return null;
    }
}
