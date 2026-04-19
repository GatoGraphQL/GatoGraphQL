<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractTransientObjectObjectTypeResolver;
use PoPWPSchema\Blocks\RelationalTypeDataLoaders\ObjectType\BlockTypeObjectTypeDataLoader;

class BlockTypeObjectTypeResolver extends AbstractTransientObjectObjectTypeResolver
{
    private ?BlockTypeObjectTypeDataLoader $blockTypeObjectTypeDataLoader = null;

    final protected function getBlockTypeObjectTypeDataLoader(): BlockTypeObjectTypeDataLoader
    {
        if ($this->blockTypeObjectTypeDataLoader === null) {
            /** @var BlockTypeObjectTypeDataLoader */
            $blockTypeObjectTypeDataLoader = $this->instanceManager->getInstance(BlockTypeObjectTypeDataLoader::class);
            $this->blockTypeObjectTypeDataLoader = $blockTypeObjectTypeDataLoader;
        }
        return $this->blockTypeObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'BlockType';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A block type registered in the WordPress block registry', 'blocks');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getBlockTypeObjectTypeDataLoader();
    }
}
