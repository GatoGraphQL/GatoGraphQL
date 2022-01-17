<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagTypeDataLoader;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;

class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

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
}
