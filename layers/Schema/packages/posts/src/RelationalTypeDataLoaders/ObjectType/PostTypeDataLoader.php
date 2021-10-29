<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowirePostTypeDataLoader(
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->getPostTypeAPI()->getPosts($query, $options);
    }
}
