<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Overrides\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader as UpstreamCommentTypeDataLoader;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;

class CommentTypeDataLoader extends UpstreamCommentTypeDataLoader
{
    /**
     * @return string[]
     */
    protected function getAllCustomPostStatuses(): array
    {
        return array_merge(
            parent::getAllCustomPostStatuses(),
            [
                CustomPostStatus::INHERIT,
            ]
        );
    }
}
