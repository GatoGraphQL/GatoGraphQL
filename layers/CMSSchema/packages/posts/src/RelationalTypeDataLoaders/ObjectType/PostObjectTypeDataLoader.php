<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostObjectTypeDataLoader;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostObjectTypeDataLoader extends AbstractCustomPostObjectTypeDataLoader
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getPostTypeAPI()->getPosts($query, $options);
    }
}
