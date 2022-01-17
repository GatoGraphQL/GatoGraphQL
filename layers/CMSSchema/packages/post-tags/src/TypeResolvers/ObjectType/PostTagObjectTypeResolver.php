<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagTypeDataLoader;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\AbstractTagObjectTypeResolver;

class PostTagObjectTypeResolver extends AbstractTagObjectTypeResolver
{
    private ?PostTagTypeDataLoader $postTagTypeDataLoader = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeDataLoader(PostTagTypeDataLoader $postTagTypeDataLoader): void
    {
        $this->postTagTypeDataLoader = $postTagTypeDataLoader;
    }
    final protected function getPostTagTypeDataLoader(): PostTagTypeDataLoader
    {
        return $this->postTagTypeDataLoader ??= $this->instanceManager->getInstance(PostTagTypeDataLoader::class);
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }

    public function getTypeName(): string
    {
        return 'PostTag';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a tag, added to a post', 'post-tags');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagTypeDataLoader();
    }
}
