<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\GenericCustomPosts\RelationalTypeDataLoaders\ObjectType\GenericCustomPostTypeDataLoader;

class GenericCustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?GenericCustomPostTypeDataLoader $genericCustomPostTypeDataLoader = null;

    final public function setGenericCustomPostTypeDataLoader(GenericCustomPostTypeDataLoader $genericCustomPostTypeDataLoader): void
    {
        $this->genericCustomPostTypeDataLoader = $genericCustomPostTypeDataLoader;
    }
    final protected function getGenericCustomPostTypeDataLoader(): GenericCustomPostTypeDataLoader
    {
        return $this->genericCustomPostTypeDataLoader ??= $this->instanceManager->getInstance(GenericCustomPostTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Any custom post, with or without its own type for the schema', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostTypeDataLoader();
    }
}
