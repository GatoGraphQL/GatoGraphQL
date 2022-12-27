<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagTypeDataLoader;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\AbstractTagObjectTypeResolver;

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
        /** @var PostTagTypeDataLoader */
        return $this->postTagTypeDataLoader ??= $this->instanceManager->getInstance(PostTagTypeDataLoader::class);
    }
    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
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
        return sprintf(
            $this->__('Representation of a tag, added to a post (taxonomy: "%s")', 'post-tags'),
            $this->getPostTagTypeAPI()->getPostTagTaxonomyName()
        );
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagTypeDataLoader();
    }
}
