<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader;
use PoPCMSSchema\Tags\TypeResolvers\InterfaceType\IsTagInterfaceTypeResolver;

class TagUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?TagUnionTypeDataLoader $tagUnionTypeDataLoader = null;
    private ?IsTagInterfaceTypeResolver $isTagInterfaceTypeResolver = null;

    final public function setTagUnionTypeDataLoader(TagUnionTypeDataLoader $tagUnionTypeDataLoader): void
    {
        $this->tagUnionTypeDataLoader = $tagUnionTypeDataLoader;
    }
    final protected function getTagUnionTypeDataLoader(): TagUnionTypeDataLoader
    {
        /** @var TagUnionTypeDataLoader */
        return $this->tagUnionTypeDataLoader ??= $this->instanceManager->getInstance(TagUnionTypeDataLoader::class);
    }
    final public function setIsTagInterfaceTypeResolver(IsTagInterfaceTypeResolver $isTagInterfaceTypeResolver): void
    {
        $this->isTagInterfaceTypeResolver = $isTagInterfaceTypeResolver;
    }
    final protected function getIsTagInterfaceTypeResolver(): IsTagInterfaceTypeResolver
    {
        /** @var IsTagInterfaceTypeResolver */
        return $this->isTagInterfaceTypeResolver ??= $this->instanceManager->getInstance(IsTagInterfaceTypeResolver::class);
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
            $this->getIsTagInterfaceTypeResolver(),
        ];
    }
}
