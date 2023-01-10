<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Categories\RelationalTypeDataLoaders\UnionType\CategoryUnionTypeDataLoader;
use PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver;

class CategoryUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?CategoryUnionTypeDataLoader $categoryUnionTypeDataLoader = null;
    private ?CategoryInterfaceTypeResolver $categoryInterfaceTypeResolver = null;

    final public function setCategoryUnionTypeDataLoader(CategoryUnionTypeDataLoader $categoryUnionTypeDataLoader): void
    {
        $this->categoryUnionTypeDataLoader = $categoryUnionTypeDataLoader;
    }
    final protected function getCategoryUnionTypeDataLoader(): CategoryUnionTypeDataLoader
    {
        /** @var CategoryUnionTypeDataLoader */
        return $this->categoryUnionTypeDataLoader ??= $this->instanceManager->getInstance(CategoryUnionTypeDataLoader::class);
    }
    final public function setCategoryInterfaceTypeResolver(CategoryInterfaceTypeResolver $categoryInterfaceTypeResolver): void
    {
        $this->categoryInterfaceTypeResolver = $categoryInterfaceTypeResolver;
    }
    final protected function getCategoryInterfaceTypeResolver(): CategoryInterfaceTypeResolver
    {
        /** @var CategoryInterfaceTypeResolver */
        return $this->categoryInterfaceTypeResolver ??= $this->instanceManager->getInstance(CategoryInterfaceTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CategoryUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'category\' type resolvers', 'categories');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCategoryUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getCategoryInterfaceTypeResolver(),
        ];
    }
}
