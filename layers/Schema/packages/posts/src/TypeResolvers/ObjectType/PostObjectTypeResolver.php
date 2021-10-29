<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?PostTypeDataLoader $postTypeDataLoader = null;

    public function setPostTypeDataLoader(PostTypeDataLoader $postTypeDataLoader): void
    {
        $this->postTypeDataLoader = $postTypeDataLoader;
    }
    protected function getPostTypeDataLoader(): PostTypeDataLoader
    {
        return $this->postTypeDataLoader ??= $this->instanceManager->getInstance(PostTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Post';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Representation of a post', 'posts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTypeDataLoader();
    }
}
