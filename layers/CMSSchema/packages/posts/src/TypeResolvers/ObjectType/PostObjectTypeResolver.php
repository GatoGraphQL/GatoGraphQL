<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostObjectTypeDataLoader;

class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PostObjectTypeDataLoader $postObjectTypeDataLoader = null;

    final protected function getPostObjectTypeDataLoader(): PostObjectTypeDataLoader
    {
        if ($this->postObjectTypeDataLoader === null) {
            /** @var PostObjectTypeDataLoader */
            $postObjectTypeDataLoader = $this->instanceManager->getInstance(PostObjectTypeDataLoader::class);
            $this->postObjectTypeDataLoader = $postObjectTypeDataLoader;
        }
        return $this->postObjectTypeDataLoader;
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
        return $this->getPostObjectTypeDataLoader();
    }
}
