<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;

class CategoryUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CategoryUnionTypeResolver $categoryUnionTypeResolver = null;

    final public function setCategoryUnionTypeResolver(CategoryUnionTypeResolver $categoryUnionTypeResolver): void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    final protected function getCategoryUnionTypeResolver(): CategoryUnionTypeResolver
    {
        /** @var CategoryUnionTypeResolver */
        return $this->categoryUnionTypeResolver ??= $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCategoryUnionTypeResolver();
    }
}
