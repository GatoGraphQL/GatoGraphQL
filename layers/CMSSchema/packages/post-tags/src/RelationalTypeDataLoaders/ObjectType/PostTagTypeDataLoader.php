<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    public function getTagListTypeAPI(): TagListTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
}
