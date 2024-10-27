<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader = null;
    private ?CustomPostInterfaceTypeResolver $customPostInterfaceTypeResolver = null;

    final protected function getCustomPostUnionTypeDataLoader(): CustomPostUnionTypeDataLoader
    {
        if ($this->customPostUnionTypeDataLoader === null) {
            /** @var CustomPostUnionTypeDataLoader */
            $customPostUnionTypeDataLoader = $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
            $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
        }
        return $this->customPostUnionTypeDataLoader;
    }
    final protected function getCustomPostInterfaceTypeResolver(): CustomPostInterfaceTypeResolver
    {
        if ($this->customPostInterfaceTypeResolver === null) {
            /** @var CustomPostInterfaceTypeResolver */
            $customPostInterfaceTypeResolver = $this->instanceManager->getInstance(CustomPostInterfaceTypeResolver::class);
            $this->customPostInterfaceTypeResolver = $customPostInterfaceTypeResolver;
        }
        return $this->customPostInterfaceTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CustomPostUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getCustomPostInterfaceTypeResolver(),
        ];
    }
}
