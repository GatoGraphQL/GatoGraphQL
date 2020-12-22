<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\TypeDataLoaders;

use PoPSchema\Posts\TypeDataLoaders\PostTypeDataLoader;
use PoPSchema\LocationPosts\Facades\LocationPostTypeAPIFacade;

class LocationPostTypeDataLoader extends PostTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        $locationPostTypeAPI = LocationPostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return (array)$locationPostTypeAPI->getLocationPosts($query);
    }

    public function executeQuery($query, array $options = [])
    {
        $locationPostTypeAPI = LocationPostTypeAPIFacade::getInstance();
        return $locationPostTypeAPI->getLocationPosts($query, $options);
    }
}
