<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractTransientObjectObjectTypeResolver;
use PoPWPSchema\Blocks\RelationalTypeDataLoaders\ObjectType\BlockTypeAttributeObjectTypeDataLoader;

class BlockTypeAttributeObjectTypeResolver extends AbstractTransientObjectObjectTypeResolver
{
    private ?BlockTypeAttributeObjectTypeDataLoader $blockTypeAttributeObjectTypeDataLoader = null;

    final protected function getBlockTypeAttributeObjectTypeDataLoader(): BlockTypeAttributeObjectTypeDataLoader
    {
        if ($this->blockTypeAttributeObjectTypeDataLoader === null) {
            /** @var BlockTypeAttributeObjectTypeDataLoader */
            $blockTypeAttributeObjectTypeDataLoader = $this->instanceManager->getInstance(BlockTypeAttributeObjectTypeDataLoader::class);
            $this->blockTypeAttributeObjectTypeDataLoader = $blockTypeAttributeObjectTypeDataLoader;
        }
        return $this->blockTypeAttributeObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'BlockTypeAttribute';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A registered attribute of a block type, as defined in its block.json schema', 'blocks');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getBlockTypeAttributeObjectTypeDataLoader();
    }
}
