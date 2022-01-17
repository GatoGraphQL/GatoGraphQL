<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->getPostTypeAPI()->getPosts($query, $options);
    }
}
