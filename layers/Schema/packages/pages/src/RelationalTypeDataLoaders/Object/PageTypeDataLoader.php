<?php

declare(strict_types=1);

namespace PoPSchema\Pages\RelationalTypeDataLoaders\Object;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\Object\AbstractCustomPostTypeDataLoader;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function executeQuery($query, array $options = []): array
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPages($query, $options);
    }
}
