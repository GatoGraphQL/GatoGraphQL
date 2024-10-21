<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostObjectTypeDataLoader as UpstreamCustomPostObjectTypeDataLoader;
use PoPWPSchema\CustomPosts\Enums\NonEnumerableCustomPostStatus;

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
                NonEnumerableCustomPostStatus::AUTO_DRAFT
            ]
        );
        return $queryToRetrieveObjectsForIDs;
    }
}
