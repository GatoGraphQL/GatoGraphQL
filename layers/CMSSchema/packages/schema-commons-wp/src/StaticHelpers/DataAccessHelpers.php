<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\StaticHelpers;

use WP_Comment;

class DataAccessHelpers
{
    /**
     * If the GMT date is stored as "0000-00-00 00:00:00",
     * then use the non-GMT date
     */
    public static function getCommentDate(WP_Comment $comment, bool $gmt = false): string
    {
        return $gmt && ($comment->comment_date_gmt !== "0000-00-00 00:00:00") ? $comment->comment_date_gmt : $comment->comment_date;
    }
}
