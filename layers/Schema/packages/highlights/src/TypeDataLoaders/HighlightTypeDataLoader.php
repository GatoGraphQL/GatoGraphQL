<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeDataLoaders;

use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;

class HighlightTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function getDataFromIdsQuery(array $ids): array
    {
        $query = parent::getDataFromIdsQuery($ids);
        $query['custompost-types'] = array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT);
        return $query;
    }

    /**
     * Function to override
     */
    public function getQuery($query_args): array
    {
        $query = parent::getQuery($query_args);

        $query['custompost-types'] = array(POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT);

        return $query;
    }
}
