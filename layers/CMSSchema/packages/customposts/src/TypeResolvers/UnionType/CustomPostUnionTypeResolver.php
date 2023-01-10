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

    final public function setCustomPostUnionTypeDataLoader(CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader): void
    {
        $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
    }
    final protected function getCustomPostUnionTypeDataLoader(): CustomPostUnionTypeDataLoader
    {
        /** @var CustomPostUnionTypeDataLoader */
        return $this->customPostUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
    }
    final public function setCustomPostInterfaceTypeResolver(CustomPostInterfaceTypeResolver $customPostInterfaceTypeResolver): void
    {
        $this->customPostInterfaceTypeResolver = $customPostInterfaceTypeResolver;
    }
    final protected function getCustomPostInterfaceTypeResolver(): CustomPostInterfaceTypeResolver
    {
        /** @var CustomPostInterfaceTypeResolver */
        return $this->customPostInterfaceTypeResolver ??= $this->instanceManager->getInstance(CustomPostInterfaceTypeResolver::class);
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
