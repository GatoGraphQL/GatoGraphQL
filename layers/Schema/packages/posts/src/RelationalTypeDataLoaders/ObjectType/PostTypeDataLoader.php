<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowirePostTypeDataLoader(
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->postTypeAPI->getPosts($query, $options);
    }
}
