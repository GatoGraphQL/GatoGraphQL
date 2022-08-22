<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader = null;
    private ?IsCustomPostInterfaceTypeResolver $isCustomPostInterfaceTypeResolver = null;

    final public function setCustomPostUnionTypeDataLoader(CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader): void
    {
        $this->customPostUnionTypeDataLoader = $customPostUnionTypeDataLoader;
    }
    final protected function getCustomPostUnionTypeDataLoader(): CustomPostUnionTypeDataLoader
    {
        return $this->customPostUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
    }
    final public function setIsCustomPostInterfaceTypeResolver(IsCustomPostInterfaceTypeResolver $isCustomPostInterfaceTypeResolver): void
    {
        $this->isCustomPostInterfaceTypeResolver = $isCustomPostInterfaceTypeResolver;
    }
    final protected function getIsCustomPostInterfaceTypeResolver(): IsCustomPostInterfaceTypeResolver
    {
        return $this->isCustomPostInterfaceTypeResolver ??= $this->instanceManager->getInstance(IsCustomPostInterfaceTypeResolver::class);
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
            $this->getIsCustomPostInterfaceTypeResolver(),
        ];
    }
}
