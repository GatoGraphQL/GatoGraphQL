<?php

declare(strict_types=1);

namespace PoPSchema\Pages\RelationalTypeDataLoaders;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\AbstractCustomPostTypeDataLoader;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function executeQuery($query, array $options = []): array
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPages($query, $options);
    }
}
