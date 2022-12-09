<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Overrides\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType\CommentTypeDataLoader as UpstreamCommentTypeDataLoader;

class CommentTypeDataLoader extends UpstreamCommentTypeDataLoader
{
    /**
     * From the WordPress docs:
     *
     *   > Post status or array of post statuses to retrieve affiliated comments for.
     *   > Pass 'any' to match any value.
     *
     * @see https://developer.wordpress.org/reference/classes/wp_comment_query/__construct/
     *
     * @return string[]
     */
    protected function getAllCustomPostStatuses(): array
    {
        return [
            'any',
        ];
        // return array_merge(
        //     parent::getAllCustomPostStatuses(),
        //     [
        //         CustomPostStatus::INHERIT,
        //     ]
        // );
    }
}
