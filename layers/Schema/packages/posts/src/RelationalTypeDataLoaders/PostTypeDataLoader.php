<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function executeQuery($query, array $options = []): array
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPosts($query, $options);
    }
}
