<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\AbstractDeleteCategoryTermInputObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\EnumType\PostCategoryTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractDeletePostCategoryTermInputObjectTypeResolver extends AbstractDeleteCategoryTermInputObjectTypeResolver implements DeletePostCategoryTermInputObjectTypeResolverInterface
{
    private ?PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver = null;

    final protected function getPostCategoryTaxonomyEnumStringScalarTypeResolver(): PostCategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->postCategoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var PostCategoryTaxonomyEnumStringScalarTypeResolver */
            $postCategoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(PostCategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->postCategoryTaxonomyEnumStringScalarTypeResolver;
    }

    protected function getTaxonomyInputObjectTypeResolver(): InputTypeResolverInterface
    {
        return $this->getPostCategoryTaxonomyEnumStringScalarTypeResolver();
    }
}
