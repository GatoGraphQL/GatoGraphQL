<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeDataLoaders;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;

class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $pageTypeAPI->getPages($query);
    }

    public function executeQuery($query, array $options = [])
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getPages($query, $options);
    }
}
