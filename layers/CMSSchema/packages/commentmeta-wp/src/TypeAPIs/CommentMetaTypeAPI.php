<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaWP\TypeAPIs;

use PoPCMSSchema\CommentMeta\TypeAPIs\AbstractCommentMetaTypeAPI;
use WP_Comment;

class CommentMetaTypeAPI extends AbstractCommentMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetCommentMeta(string|int|object $commentObjectOrID, string $key, bool $single = false): mixed
    {
        if (is_object($commentObjectOrID)) {
            /** @var WP_Comment */
            $comment = $commentObjectOrID;
            $commentID = $comment->comment_ID;
        } else {
            $commentID = $commentObjectOrID;
        }

        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existent and return null
        $value = \get_comment_meta((int)$commentID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
