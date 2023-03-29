<?php

declare(strict_types=1);

namespace PoPSchema\Stances\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostObjectTypeDataLoader;

class StanceObjectTypeDataLoader extends AbstractCustomPostObjectTypeDataLoader
{
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = parent::getQueryToRetrieveObjectsForIDs($ids);
        $query['custompost-types'] = array(POP_USERSTANCE_POSTTYPE_USERSTANCE);
        return $query;
    }

    /**
     * @param array<string,mixed> $query_args
     * @return array<string,mixed>
     */
    public function getQuery(array $query_args): array
    {
        $query = parent::getQuery($query_args);

        $query['custompost-types'] = array(POP_USERSTANCE_POSTTYPE_USERSTANCE);

        return $query;
    }
}
