<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader;
use PoPCMSSchema\Tags\TypeResolvers\InterfaceType\TagInterfaceTypeResolver;

class TagUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?TagUnionTypeDataLoader $tagUnionTypeDataLoader = null;
    private ?TagInterfaceTypeResolver $tagInterfaceTypeResolver = null;

    final protected function getTagUnionTypeDataLoader(): TagUnionTypeDataLoader
    {
        if ($this->tagUnionTypeDataLoader === null) {
            /** @var TagUnionTypeDataLoader */
            $tagUnionTypeDataLoader = $this->instanceManager->getInstance(TagUnionTypeDataLoader::class);
            $this->tagUnionTypeDataLoader = $tagUnionTypeDataLoader;
        }
        return $this->tagUnionTypeDataLoader;
    }
    final protected function getTagInterfaceTypeResolver(): TagInterfaceTypeResolver
    {
        if ($this->tagInterfaceTypeResolver === null) {
            /** @var TagInterfaceTypeResolver */
            $tagInterfaceTypeResolver = $this->instanceManager->getInstance(TagInterfaceTypeResolver::class);
            $this->tagInterfaceTypeResolver = $tagInterfaceTypeResolver;
        }
        return $this->tagInterfaceTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'TagUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'tag\' type resolvers', 'tags');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTagUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getTagInterfaceTypeResolver(),
        ];
    }
}
