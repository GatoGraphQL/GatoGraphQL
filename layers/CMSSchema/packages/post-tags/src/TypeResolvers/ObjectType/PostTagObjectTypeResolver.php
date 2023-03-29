<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagObjectTypeDataLoader;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\AbstractTagObjectTypeResolver;

class PostTagObjectTypeResolver extends AbstractTagObjectTypeResolver
{
    private ?PostTagObjectTypeDataLoader $postTagObjectTypeDataLoader = null;
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagObjectTypeDataLoader(PostTagObjectTypeDataLoader $postTagObjectTypeDataLoader): void
    {
        $this->postTagObjectTypeDataLoader = $postTagObjectTypeDataLoader;
    }
    final protected function getPostTagObjectTypeDataLoader(): PostTagObjectTypeDataLoader
    {
        /** @var PostTagObjectTypeDataLoader */
        return $this->postTagObjectTypeDataLoader ??= $this->instanceManager->getInstance(PostTagObjectTypeDataLoader::class);
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
        return $this->getPostTagObjectTypeDataLoader();
    }
}
