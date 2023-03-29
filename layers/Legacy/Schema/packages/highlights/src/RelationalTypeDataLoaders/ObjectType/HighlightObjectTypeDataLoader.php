<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostObjectTypeDataLoader;

class HighlightObjectTypeDataLoader extends AbstractCustomPostObjectTypeDataLoader
{
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $query = parent::getQueryToRetrieveObjectsForIDs($ids);
        $query['custompost-types'] = array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT);
        return $query;
    }

    /**
     * @param array<string,mixed> $query_args
     * @return array<string,mixed>
     */
    public function getQuery(array $query_args): array
    {
        $query = parent::getQuery($query_args);

        $query['custompost-types'] = array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT);

        return $query;
    }
}
