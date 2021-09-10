<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function executeQuery($query, array $options = []): array
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPosts($query, $options);
    }
}
