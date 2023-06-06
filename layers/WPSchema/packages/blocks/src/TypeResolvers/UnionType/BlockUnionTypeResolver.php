<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\UnionType;

use PoPWPSchema\Blocks\RelationalTypeDataLoaders\UnionType\BlockUnionTypeDataLoader;
use PoPWPSchema\Blocks\TypeResolvers\InterfaceType\BlockInterfaceTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;

class BlockUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?BlockInterfaceTypeResolver $blockInterfaceTypeResolver = null;
    private ?BlockUnionTypeDataLoader $blockUnionTypeDataLoader = null;

    final public function setBlockInterfaceTypeResolver(BlockInterfaceTypeResolver $blockInterfaceTypeResolver): void
    {
        $this->blockInterfaceTypeResolver = $blockInterfaceTypeResolver;
    }
    final protected function getBlockInterfaceTypeResolver(): BlockInterfaceTypeResolver
    {
        /** @var BlockInterfaceTypeResolver */
        return $this->blockInterfaceTypeResolver ??= $this->instanceManager->getInstance(BlockInterfaceTypeResolver::class);
    }
    final public function setBlockUnionTypeDataLoader(BlockUnionTypeDataLoader $blockUnionTypeDataLoader): void
    {
        $this->blockUnionTypeDataLoader = $blockUnionTypeDataLoader;
    }
    final protected function getBlockUnionTypeDataLoader(): BlockUnionTypeDataLoader
    {
        /** @var BlockUnionTypeDataLoader */
        return $this->blockUnionTypeDataLoader ??= $this->instanceManager->getInstance(BlockUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'BlockUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Block\' types', 'blocks');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getBlockUnionTypeDataLoader();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getBlockInterfaceTypeResolver(),
        ];
    }
}
