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

    final public function setTagUnionTypeDataLoader(TagUnionTypeDataLoader $tagUnionTypeDataLoader): void
    {
        $this->tagUnionTypeDataLoader = $tagUnionTypeDataLoader;
    }
    final protected function getTagUnionTypeDataLoader(): TagUnionTypeDataLoader
    {
        /** @var TagUnionTypeDataLoader */
        return $this->tagUnionTypeDataLoader ??= $this->instanceManager->getInstance(TagUnionTypeDataLoader::class);
    }
    final public function setTagInterfaceTypeResolver(TagInterfaceTypeResolver $tagInterfaceTypeResolver): void
    {
        $this->tagInterfaceTypeResolver = $tagInterfaceTypeResolver;
    }
    final protected function getTagInterfaceTypeResolver(): TagInterfaceTypeResolver
    {
        /** @var TagInterfaceTypeResolver */
        return $this->tagInterfaceTypeResolver ??= $this->instanceManager->getInstance(TagInterfaceTypeResolver::class);
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
