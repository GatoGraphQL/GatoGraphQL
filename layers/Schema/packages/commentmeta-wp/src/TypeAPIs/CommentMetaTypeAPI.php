<?php

declare(strict_types=1);

namespace PoPSchema\CommentMetaWP\TypeAPIs;

use PoPSchema\CommentMeta\TypeAPIs\AbstractCommentMetaTypeAPI;

class CommentMetaTypeAPI extends AbstractCommentMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    public function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        $value = \get_comment_meta($commentID, $key, $single);
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
