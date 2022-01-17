<?php

declare(strict_types=1);

namespace PoPSchema\Stances\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;

class StanceTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = parent::getQueryToRetrieveObjectsForIDs($ids);
        $query['custompost-types'] = array(POP_USERSTANCE_POSTTYPE_USERSTANCE);
        return $query;
    }

    /**
     * Function to override
     */
    public function getQuery($query_args): array
    {
        $query = parent::getQuery($query_args);

        $query['custompost-types'] = array(POP_USERSTANCE_POSTTYPE_USERSTANCE);

        return $query;
    }
}
