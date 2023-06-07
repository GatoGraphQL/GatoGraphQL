<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\RelationalTypeDataLoaders\UnionType;

use PoPWPSchema\Blocks\TypeResolvers\UnionType\BlockUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class BlockUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?BlockUnionTypeResolver $blockUnionTypeResolver = null;

    final public function setBlockUnionTypeResolver(BlockUnionTypeResolver $blockUnionTypeResolver): void
    {
        $this->blockUnionTypeResolver = $blockUnionTypeResolver;
    }
    final protected function getBlockUnionTypeResolver(): BlockUnionTypeResolver
    {
        /** @var BlockUnionTypeResolver */
        return $this->blockUnionTypeResolver ??= $this->instanceManager->getInstance(BlockUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getBlockUnionTypeResolver();
    }
}
