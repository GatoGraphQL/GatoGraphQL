<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader;

class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PostTypeDataLoader $postTypeDataLoader = null;

    final public function setPostTypeDataLoader(PostTypeDataLoader $postTypeDataLoader): void
    {
        $this->postTypeDataLoader = $postTypeDataLoader;
    }
    final protected function getPostTypeDataLoader(): PostTypeDataLoader
    {
        return $this->postTypeDataLoader ??= $this->instanceManager->getInstance(PostTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Post';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a post', 'posts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTypeDataLoader();
    }
}
