<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostObjectTypeDataLoader as UpstreamCustomPostObjectTypeDataLoader;

class CustomPostObjectTypeDataLoader extends UpstreamCustomPostObjectTypeDataLoader
{
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        $queryToRetrieveObjectsForIDs = parent::getQueryToRetrieveObjectsForIDs($ids);
        $queryToRetrieveObjectsForIDs['status'] = array_merge(
            $queryToRetrieveObjectsForIDs['status'],
            [
                'auto-draft'
            ]
        );
        return $queryToRetrieveObjectsForIDs;
    }
}
