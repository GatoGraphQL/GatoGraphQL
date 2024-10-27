<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ObjectType;

use PoPWPSchema\Blocks\RelationalTypeDataLoaders\ObjectType\GeneralBlockObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GeneralBlockObjectTypeResolver extends AbstractBlockObjectTypeResolver
{
    private ?GeneralBlockObjectTypeDataLoader $genericBlockObjectTypeDataLoader = null;

    final protected function getGeneralBlockObjectTypeDataLoader(): GeneralBlockObjectTypeDataLoader
    {
        if ($this->genericBlockObjectTypeDataLoader === null) {
            /** @var GeneralBlockObjectTypeDataLoader */
            $genericBlockObjectTypeDataLoader = $this->instanceManager->getInstance(GeneralBlockObjectTypeDataLoader::class);
            $this->genericBlockObjectTypeDataLoader = $genericBlockObjectTypeDataLoader;
        }
        return $this->genericBlockObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GeneralBlock';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a Block that works for all blocks, retrieving the block attributes as a non-strictly-typed JSON object', 'blocks');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGeneralBlockObjectTypeDataLoader();
    }
}
