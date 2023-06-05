<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ObjectType;

use PoPWPSchema\Blocks\RelationalTypeDataLoaders\ObjectType\GeneralBlockObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GeneralBlockObjectTypeResolver extends AbstractBlockObjectTypeResolver
{
    private ?GeneralBlockObjectTypeDataLoader $genericBlockObjectTypeDataLoader = null;

    final public function setGeneralBlockObjectTypeDataLoader(GeneralBlockObjectTypeDataLoader $genericBlockObjectTypeDataLoader): void
    {
        $this->genericBlockObjectTypeDataLoader = $genericBlockObjectTypeDataLoader;
    }
    final protected function getGeneralBlockObjectTypeDataLoader(): GeneralBlockObjectTypeDataLoader
    {
        /** @var GeneralBlockObjectTypeDataLoader */
        return $this->genericBlockObjectTypeDataLoader ??= $this->instanceManager->getInstance(GeneralBlockObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'GeneralBlock';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Type that contains all the block data (name, attributes and inner blocks)', 'blocks');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGeneralBlockObjectTypeDataLoader();
    }
}
