<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;

class CategoryUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CategoryUnionTypeResolver $categoryUnionTypeResolver = null;

    final protected function getCategoryUnionTypeResolver(): CategoryUnionTypeResolver
    {
        if ($this->categoryUnionTypeResolver === null) {
            /** @var CategoryUnionTypeResolver */
            $categoryUnionTypeResolver = $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
            $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
        }
        return $this->categoryUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCategoryUnionTypeResolver();
    }
}
